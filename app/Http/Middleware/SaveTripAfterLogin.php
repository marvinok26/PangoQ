<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;

class SaveTripAfterLogin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Process the request first to allow authentication to complete
        $response = $next($request);

        // Only proceed if user is authenticated
        if (Auth::check()) {
            $user = Auth::user();

            // Restore social authentication trip data if present
            if (session()->has('social_auth_trip_data')) {
                $tripData = session('social_auth_trip_data');
                foreach ($tripData as $key => $value) {
                    session([$key => $value]);
                }
                session()->forget('social_auth_trip_data');
                Log::info('Restored trip data from social auth session');
            }

            // Check if we have trip data to save
            $hasMinimumTripData = session('selected_trip_type') && 
                                (session('selected_destination') || session('selected_trip_template'));
            $shouldSave = session('trip_data_not_saved', false);

            if ($shouldSave && $hasMinimumTripData) {
                // Instead of processing immediately, defer it to after response is sent
                $this->deferTripCreation($user->id);
                
                // Set flash message and clear flag immediately
                session()->flash('success', 'Your trip is being created! You\'ll see it in your trips shortly.');
                session()->forget('trip_data_not_saved');
                
                Log::info('Trip creation deferred for user', ['user_id' => $user->id]);
            } else {
                // Clear the flag if no data to save but preserve the data for manual creation
                session()->forget('trip_data_not_saved');
                
                // If user has trip data but no flag set, set a notification
                if ($hasMinimumTripData && !session()->has('trip_restoration_notified')) {
                    session()->flash('info', 'Your trip planning progress has been preserved. You can continue where you left off.');
                    session(['trip_restoration_notified' => true]);
                    Log::info('Trip data preserved for user after login', ['user_id' => $user->id]);
                }
            }
        }

        return $response;
    }

    /**
     * Defer trip creation to after response is sent
     */
    private function deferTripCreation($userId)
    {
        // Gather all session data
        $tripData = [
            'user_id' => $userId,
            'trip_type' => session('selected_trip_type'),
            'destination' => session('selected_destination'),
            'template_id' => session('selected_trip_template'),
            'trip_details' => session('trip_details'),
            'trip_activities' => session('trip_activities'),
            'trip_invites' => session('trip_invites'),
            'selected_optional_activities' => session('selected_optional_activities'),
            'trip_total_price' => session('trip_total_price', 0)
        ];

        // Use Laravel's built-in dispatch after response
        dispatch(function() use ($tripData) {
            $this->createTripFromSessionData($tripData);
        })->afterResponse();

        // Clear session data immediately
        $this->clearTripSessionData();
    }

    /**
     * Create trip from session data (runs after response is sent)
     */
    private function createTripFromSessionData($tripData)
    {
        try {
            $user = \App\Models\User::find($tripData['user_id']);
            if (!$user) {
                Log::error('User not found for trip creation', ['user_id' => $tripData['user_id']]);
                return;
            }

            // Create the trip
            $trip = $this->createTrip($user, $tripData);
            
            // Create savings wallet
            $this->createSavingsWallet($trip);

            // Create itineraries
            if ($tripData['trip_type'] === 'pre_planned' && $tripData['template_id']) {
                $this->createItinerariesFromTemplate($trip);
            } elseif ($tripData['trip_activities']) {
                $this->createItinerariesFromSessionData($trip, $tripData['trip_activities']);
            } else {
                $this->createEmptyItineraries($trip);
            }

            // Process invites
            if ($tripData['trip_invites']) {
                $this->processInvites($trip, $tripData['trip_invites']);
            }

            // Add creator as member
            \App\Models\TripMember::create([
                'trip_id' => $trip->id,
                'user_id' => $user->id,
                'role' => 'organizer',
                'invitation_status' => 'accepted'
            ]);

            Log::info('Trip created successfully in background', [
                'trip_id' => $trip->id,
                'user_id' => $user->id
            ]);

        } catch (\Exception $e) {
            Log::error('Error creating trip from session data', [
                'error' => $e->getMessage(),
                'user_id' => $tripData['user_id'] ?? null
            ]);
        }
    }

    /**
     * Create the main trip record
     */
    private function createTrip($user, $tripData)
    {
        $trip = new \App\Models\Trip();
        $trip->creator_id = $user->id;
        $trip->planning_type = $tripData['trip_type'];

        // Set template if pre-planned
        if ($tripData['trip_type'] === 'pre_planned' && $tripData['template_id']) {
            $trip->trip_template_id = $tripData['template_id'];
        }

        // Set destination
        if ($tripData['destination']) {
            $trip->destination = $tripData['destination']['name'];
        }

        // Set trip details
        $tripDetails = $tripData['trip_details'] ?? [];
        $trip->title = $tripDetails['title'] ?? ('Trip to ' . ($tripData['destination']['name'] ?? 'Unknown'));
        $trip->description = $tripDetails['description'] ?? null;
        $trip->start_date = $tripDetails['start_date'] ?? \Carbon\Carbon::now()->addWeeks(2);
        $trip->end_date = $tripDetails['end_date'] ?? \Carbon\Carbon::now()->addWeeks(3);
        $trip->budget = $tripDetails['budget'] ?? $tripData['trip_total_price'] ?? null;
        $trip->total_cost = $tripDetails['total_cost'] ?? $tripData['trip_total_price'] ?? $trip->budget;
        $trip->status = 'planning';

        // Save selected optional activities if pre-planned
        if ($tripData['selected_optional_activities']) {
            $trip->selected_optional_activities = json_encode($tripData['selected_optional_activities']);
        }

        $trip->save();
        return $trip;
    }

    /**
     * Create a savings wallet for the trip
     */
    private function createSavingsWallet($trip)
    {
        \App\Models\SavingsWallet::create([
            'trip_id' => $trip->id,
            'name' => ['en' => 'Savings for ' . $trip->title],
            'minimum_goal' => $trip->budget ?? 0,
            'current_amount' => 0,
            'target_date' => $trip->start_date,
            'contribution_frequency' => 'weekly',
            'currency' => 'USD',
        ]);
    }

    /**
     * Create itineraries from template
     */
    private function createItinerariesFromTemplate($trip)
    {
        if (method_exists($trip, 'createItinerariesFromTemplate')) {
            $trip->createItinerariesFromTemplate();
        }
    }

    /**
     * Create itineraries and activities from session data
     */
    private function createItinerariesFromSessionData($trip, $tripActivities)
    {
        $startDate = \Carbon\Carbon::parse($trip->start_date);

        foreach ($tripActivities as $day => $activities) {
            $date = clone $startDate;
            $date->addDays($day - 1);

            // Create an itinerary for this day
            $itinerary = \App\Models\Itinerary::create([
                'trip_id' => $trip->id,
                'title' => "Day $day: " . $trip->destination,
                'description' => "Itinerary for day $day in " . $trip->destination,
                'day_number' => $day,
                'date' => $date,
            ]);

            // Add activities for this day
            foreach ($activities as $activityData) {
                \App\Models\Activity::create([
                    'itinerary_id' => $itinerary->id,
                    'title' => $activityData['title'],
                    'description' => $activityData['description'] ?? null,
                    'location' => $activityData['location'] ?? null,
                    'start_time' => $activityData['start_time'] ?? null,
                    'end_time' => $activityData['end_time'] ?? null,
                    'cost' => $activityData['cost'] ?? null,
                    'created_by' => $trip->creator_id,
                    'category' => $activityData['category'] ?? 'activity',
                    'is_optional' => $activityData['is_optional'] ?? false,
                    'is_highlight' => $activityData['is_highlight'] ?? false,
                ]);
            }
        }
    }

    /**
     * Create empty itineraries based on trip duration
     */
    private function createEmptyItineraries($trip)
    {
        $startDate = \Carbon\Carbon::parse($trip->start_date);
        $endDate = \Carbon\Carbon::parse($trip->end_date);
        $days = $startDate->diffInDays($endDate) + 1;

        for ($day = 1; $day <= $days; $day++) {
            $date = clone $startDate;
            $date->addDays($day - 1);

            \App\Models\Itinerary::create([
                'trip_id' => $trip->id,
                'title' => "Day $day: " . $trip->destination,
                'description' => "Itinerary for day $day in " . $trip->destination,
                'day_number' => $day,
                'date' => $date,
            ]);
        }
    }

    /**
     * Process invites from session data
     */
    private function processInvites($trip, $invites)
    {
        foreach ($invites as $invite) {
            $email = $invite['email'] ?? null;
            if (!$email) continue;

            $user = \App\Models\User::where('email', $email)->first();

            \App\Models\TripMember::create([
                'trip_id' => $trip->id,
                'user_id' => $user ? $user->id : null,
                'invitation_email' => $user ? null : $email,
                'role' => 'member',
                'invitation_status' => 'pending'
            ]);
        }
    }

    /**
     * Clear trip planning session data
     */
    private function clearTripSessionData()
    {
        session()->forget([
            'selected_trip_type',
            'selected_destination',
            'selected_trip_template',
            'trip_details',
            'trip_activities',
            'trip_invites',
            'selected_optional_activities',
            'trip_total_price'
        ]);
    }
}