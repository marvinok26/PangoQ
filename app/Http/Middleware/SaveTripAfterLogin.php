<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;
use App\Models\Trip;
use App\Models\Itinerary;
use App\Models\Activity;
use App\Models\TripMember;
use App\Models\SavingsWallet;
use Carbon\Carbon;

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
        try {
            // Test Redis connection
            \Illuminate\Support\Facades\Redis::connection()->ping();
        } catch (\Exception $e) {
            // If Redis fails, log the error and continue with file sessions
            Log::error('Redis connection failed in SaveTripAfterLogin: ' . $e->getMessage());
            config(['session.driver' => 'file']);
        }
        
        // Debug logging
        Log::info('SaveTripAfterLogin middleware running', [
            'route' => $request->route()->getName(),
            'user_authenticated' => Auth::check(),
            'session_id' => session()->getId()
        ]);

        // Process the request first to allow authentication to complete
        $response = $next($request);

        // Only proceed if user is authenticated (ensuring authentication completes first)
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

            // Check if we have session data for trip planning
            $tripType = session('selected_trip_type');
            $destination = session('selected_destination');
            $tripDetails = session('trip_details');
            $tripActivities = session('trip_activities');
            $tripInvites = session('trip_invites');
            $templateId = session('selected_trip_template');
            $selectedOptionalActivities = session('selected_optional_activities', []);
            $tripTotalPrice = session('trip_total_price', 0);

            // Log session data for debugging
            Log::info('Trip session data after authentication', [
                'has_trip_type' => !empty($tripType),
                'has_destination' => !empty($destination),
                'has_trip_details' => !empty($tripDetails),
                'trip_data_not_saved' => session('trip_data_not_saved')
            ]);

            // Only save data if we have the minimum required fields and flag indicating this is new data
            $newSessionData = session('trip_data_not_saved', false);

            if ($newSessionData && $tripType) {
                try {
                    // Create a new Trip
                    $trip = new Trip();
                    $trip->creator_id = $user->id;
                    $trip->planning_type = $tripType;

                    // For pre-planned trips, set the template ID
                    if ($tripType === 'pre_planned' && $templateId) {
                        $trip->trip_template_id = $templateId;

                        // If trip_details aren't set but we have a template, we can use template data
                        if (empty($tripDetails) && $templateId) {
                            $template = \App\Models\TripTemplate::find($templateId);
                            if ($template) {
                                Log::info('Using trip template for details', ['template_id' => $templateId]);

                                // Set minimum required details from template
                                $trip->title = 'Trip to ' . $template->destination->name;
                                $trip->description = $template->description;
                                $trip->destination = $template->destination->name;
                                $trip->start_date = Carbon::now()->addWeeks(2);
                                $trip->end_date = Carbon::now()->addWeeks(2)->addDays($template->duration_days);
                                $trip->budget = $tripTotalPrice > 0 ? $tripTotalPrice : $template->base_price;
                                $trip->total_cost = $tripTotalPrice > 0 ? $tripTotalPrice : $template->base_price;
                                $trip->selected_optional_activities = json_encode($selectedOptionalActivities);

                                // Set destination from template if not provided
                                if (empty($destination)) {
                                    $destination = [
                                        'name' => $template->destination->name,
                                        'country' => $template->destination->country
                                    ];
                                }
                            }
                        }
                    }

                    // Set trip details
                    if (!empty($destination)) {
                        $trip->destination = $destination['name'];

                        // If no trip details provided, create minimal details
                        if (empty($tripDetails)) {
                            $tripDetails = [
                                'title' => 'Trip to ' . $destination['name'],
                                'start_date' => Carbon::now()->addWeeks(2),
                                'end_date' => Carbon::now()->addWeeks(3)
                            ];
                        }
                    }

                    // Set remaining trip details if provided
                    if (!empty($tripDetails)) {
                        $trip->title = $tripDetails['title'] ?? ('Trip to ' . ($destination['name'] ?? 'Unknown'));
                        $trip->description = $tripDetails['description'] ?? null;
                        $trip->start_date = $tripDetails['start_date'] ?? Carbon::now()->addWeeks(2);
                        $trip->end_date = $tripDetails['end_date'] ?? Carbon::now()->addWeeks(3);
                        $trip->budget = $tripDetails['budget'] ?? null;
                        if (isset($tripDetails['total_cost'])) {
                            $trip->total_cost = $tripDetails['total_cost'];
                        }
                        $trip->status = 'planning';

                        // Save the trip
                        $trip->save();
                        Log::info('Trip saved successfully', ['trip_id' => $trip->id]);
                        
                        // Create savings wallet for the trip
                        $this->createSavingsWallet($trip);

                        // If pre-planned, create itineraries from template
                        if ($tripType === 'pre_planned' && isset($trip->trip_template_id)) {
                            if (method_exists($trip, 'createItinerariesFromTemplate')) {
                                $trip->createItinerariesFromTemplate();
                                Log::info('Created itineraries from template');
                            }
                        }
                        // Otherwise create itineraries from session data
                        else if ($tripActivities) {
                            $this->createItinerariesFromSessionData($trip, $tripActivities);
                            Log::info('Created itineraries from session data');
                        } else {
                            // Create empty itineraries based on trip duration
                            $this->createEmptyItineraries($trip);
                            Log::info('Created empty itineraries based on trip duration');
                        }

                        // Add invites if any
                        if ($tripInvites) {
                            $this->processInvites($trip, $tripInvites);
                            Log::info('Processed trip invites');
                        }

                        // Add the creator as a trip member with organizer role
                        TripMember::create([
                            'trip_id' => $trip->id,
                            'user_id' => $user->id,
                            'role' => 'organizer',
                            'invitation_status' => 'accepted'
                        ]);
                        Log::info('Added creator as trip member');

                        // Clear session data after saving to database
                        $this->clearTripSessionData();

                        // Set success message
                        session()->flash('success', 'Your trip planning data has been saved!');

                        // Redirect to trips index if this is direct login
                        if (
                            $request->route()->getName() === 'login' ||
                            $request->route()->getName() === 'auth.callback'
                        ) {
                            return redirect()->route('trips.index');
                        }
                    }
                } catch (\Exception $e) {
                    Log::error('Error saving trip data after authentication', [
                        'error' => $e->getMessage(),
                        'trace' => $e->getTraceAsString()
                    ]);
                    
                    // Keep session data if error occurred
                    session(['trip_data_not_saved' => true]);
                }
            } else {
                // Remove the flag if processing complete or no data to save
                session()->forget('trip_data_not_saved');
            }
        }

        return $response;
    }

    /**
     * Create a savings wallet for the trip
     */
    private function createSavingsWallet($trip)
    {
        if (empty($trip->budget)) {
            $targetAmount = 0;
        } else {
            $targetAmount = $trip->budget;
        }

        SavingsWallet::create([
            'trip_id' => $trip->id,
            'name' => ['en' => 'Savings for ' . $trip->title],
            'minimum_goal' => $targetAmount,
            'current_amount' => 0,
            'target_date' => $trip->start_date,
            'contribution_frequency' => 'weekly',
            'currency' => 'USD',
        ]);
    }

    /**
     * Create itineraries and activities from session data
     */
    private function createItinerariesFromSessionData($trip, $tripActivities)
    {
        $startDate = Carbon::parse($trip->start_date);

        foreach ($tripActivities as $day => $activities) {
            $date = clone $startDate;
            $date->addDays($day - 1);

            // Create an itinerary for this day
            $itinerary = new Itinerary();
            $itinerary->trip_id = $trip->id;
            $itinerary->title = "Day $day: " . $trip->destination;
            $itinerary->description = "Itinerary for day $day in " . $trip->destination;
            $itinerary->day_number = $day;
            $itinerary->date = $date;
            $itinerary->save();

            // Add activities for this day
            foreach ($activities as $activityData) {
                $activity = new Activity();
                $activity->itinerary_id = $itinerary->id;
                $activity->title = $activityData['title'];
                $activity->description = $activityData['description'] ?? null;
                $activity->location = $activityData['location'] ?? null;
                $activity->start_time = $activityData['start_time'] ?? null;
                $activity->end_time = $activityData['end_time'] ?? null;
                $activity->cost = $activityData['cost'] ?? null;
                $activity->created_by = $trip->creator_id;
                $activity->category = $activityData['category'] ?? 'activity';
                $activity->is_optional = $activityData['is_optional'] ?? false;
                $activity->is_highlight = $activityData['is_highlight'] ?? false;
                $activity->save();
            }
        }
    }

    /**
     * Create empty itineraries based on trip duration
     */
    private function createEmptyItineraries($trip)
    {
        $startDate = Carbon::parse($trip->start_date);
        $endDate = Carbon::parse($trip->end_date);
        $days = $startDate->diffInDays($endDate) + 1;

        for ($day = 1; $day <= $days; $day++) {
            $date = clone $startDate;
            $date->addDays($day - 1);

            Itinerary::create([
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

            if ($email) {
                // Check if the user exists
                $user = \App\Models\User::where('email', $email)->first();

                TripMember::create([
                    'trip_id' => $trip->id,
                    'user_id' => $user ? $user->id : null,
                    'invitation_email' => $user ? null : $email,
                    'role' => 'member',
                    'invitation_status' => 'pending'
                ]);
            }
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
            'trip_total_price',
            'trip_data_not_saved'
        ]);
    }
}