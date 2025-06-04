<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;
use App\Models\Trip;
use App\Models\TripTemplate;
use App\Models\Destination;
use Carbon\Carbon;

class HandleTripSessionData
{
    /**
     * Handle an incoming request and process trip session data.
     */
    public function handle(Request $request, Closure $next)
    {
        // Only process if user is authenticated and has trip data to save
        if (Auth::check() && Session::get('trip_data_not_saved')) {
            try {
                $this->createTripFromSession();
                Session::forget('trip_data_not_saved');
                
                // Clear trip planning session data after saving
                $this->clearTripPlanningSession();
                
                Log::info('Trip created successfully from session data', [
                    'user_id' => Auth::id()
                ]);
                
            } catch (\Exception $e) {
                Log::error('Failed to create trip from session data', [
                    'user_id' => Auth::id(),
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
                
                // Don't clear the flag so user can try again
                Session::flash('error', 'There was an error saving your trip. Please try again.');
            }
        }

        return $next($request);
    }

    /**
     * Create a trip from session data
     */
    private function createTripFromSession()
    {
        $tripType = Session::get('selected_trip_type');
        $destination = Session::get('selected_destination');
        $tripDetails = Session::get('trip_details', []);
        $tripInvites = Session::get('trip_invites', []);
        
        if (!$tripType || !$destination) {
            throw new \Exception('Missing required trip data');
        }

        // Find or create destination
        $destinationModel = null;
        if (is_array($destination) && isset($destination['id'])) {
            $destinationModel = Destination::find($destination['id']);
        } elseif (is_string($destination)) {
            $destinationModel = Destination::where('name', $destination)->first();
        }

        if (!$destinationModel) {
            throw new \Exception('Destination not found');
        }

        // Prepare trip data
        $tripData = [
            'user_id' => Auth::id(),
            'title' => $tripDetails['title'] ?? 'Trip to ' . $destinationModel->name,
            'destination' => $destinationModel->name,
            'destination_country' => $destinationModel->country,
            'start_date' => $tripDetails['start_date'] ?? now()->addWeeks(2)->format('Y-m-d'),
            'end_date' => $tripDetails['end_date'] ?? now()->addWeeks(3)->format('Y-m-d'),
            'travelers' => $tripDetails['travelers'] ?? (count($tripInvites) + 1),
            'budget' => $tripDetails['budget'] ?? 0,
            'total_cost' => $tripDetails['total_cost'] ?? 0,
            'trip_type' => $tripType,
            'status' => 'planning',
            'is_public' => false,
        ];

        // Handle pre-planned trips
        if ($tripType === 'pre_planned') {
            $templateId = Session::get('selected_trip_template');
            $template = TripTemplate::find($templateId);
            
            if ($template) {
                $tripData['trip_template_id'] = $template->id;
                $tripData['total_cost'] = Session::get('trip_total_price', $template->base_price);
                $tripData['budget'] = max($tripData['budget'], $tripData['total_cost']);
            }
        }

        // Create the trip
        $trip = Trip::create($tripData);

        // Create itineraries and activities
        $this->createTripItineraries($trip, $tripType);

        // Send invites
        $this->processTripInvites($trip, $tripInvites);

        // Store trip ID in session for redirect
        Session::put('newly_created_trip_id', $trip->id);
        Session::flash('success', 'Your trip "' . $trip->title . '" has been created successfully!');

        return $trip;
    }

    /**
     * Create itineraries and activities for the trip
     */
    private function createTripItineraries(Trip $trip, string $tripType)
    {
        $startDate = Carbon::parse($trip->start_date);
        $endDate = Carbon::parse($trip->end_date);
        $totalDays = $startDate->diffInDays($endDate) + 1;

        if ($tripType === 'pre_planned') {
            $this->createPrePlannedItineraries($trip, $totalDays, $startDate);
        } else {
            $this->createSelfPlannedItineraries($trip, $totalDays, $startDate);
        }
    }

    /**
     * Create itineraries for pre-planned trips
     */
    private function createPrePlannedItineraries(Trip $trip, int $totalDays, Carbon $startDate)
    {
        $template = $trip->tripTemplate;
        if (!$template) return;

        $selectedOptionalActivities = Session::get('selected_optional_activities', []);

        // Group template activities by day
        $activitiesByDay = $template->activities()
            ->where('is_optional', false)
            ->get()
            ->groupBy('day_number');

        // Get optional activities
        $optionalActivities = $template->activities()
            ->where('is_optional', true)
            ->get()
            ->keyBy('id');

        for ($day = 1; $day <= $totalDays; $day++) {
            $date = $startDate->copy()->addDays($day - 1);
            
            $itinerary = $trip->itineraries()->create([
                'title' => 'Day ' . $day,
                'description' => 'Day ' . $day . ' activities in ' . $trip->destination,
                'day_number' => $day,
                'date' => $date,
            ]);

            // Add regular activities for this day
            if (isset($activitiesByDay[$day])) {
                foreach ($activitiesByDay[$day] as $templateActivity) {
                    $itinerary->activities()->create([
                        'title' => $templateActivity->title,
                        'description' => $templateActivity->description,
                        'location' => $templateActivity->location,
                        'start_time' => $templateActivity->start_time,
                        'end_time' => $templateActivity->end_time,
                        'cost' => $templateActivity->cost,
                        'category' => $templateActivity->category,
                        'type' => $templateActivity->category,
                        'is_optional' => false,
                        'is_highlight' => $templateActivity->is_highlight,
                        'created_by' => $trip->user_id,
                    ]);
                }
            }

            // Add selected optional activities for this day
            foreach ($selectedOptionalActivities as $activityId => $activityData) {
                if (isset($optionalActivities[$activityId])) {
                    $optionalActivity = $optionalActivities[$activityId];
                    if ($optionalActivity->day_number == $day) {
                        $itinerary->activities()->create([
                            'title' => $optionalActivity->title,
                            'description' => $optionalActivity->description,
                            'location' => $optionalActivity->location,
                            'start_time' => $optionalActivity->start_time,
                            'end_time' => $optionalActivity->end_time,
                            'cost' => $optionalActivity->cost,
                            'category' => $optionalActivity->category,
                            'type' => $optionalActivity->category,
                            'is_optional' => true,
                            'is_highlight' => $optionalActivity->is_highlight,
                            'created_by' => $trip->user_id,
                        ]);
                    }
                }
            }
        }
    }

    /**
     * Create itineraries for self-planned trips
     */
    private function createSelfPlannedItineraries(Trip $trip, int $totalDays, Carbon $startDate)
    {
        $tripActivities = Session::get('trip_activities', []);

        for ($day = 1; $day <= $totalDays; $day++) {
            $date = $startDate->copy()->addDays($day - 1);
            
            $itinerary = $trip->itineraries()->create([
                'title' => 'Day ' . $day,
                'description' => 'Day ' . $day . ' activities in ' . $trip->destination,
                'day_number' => $day,
                'date' => $date,
            ]);

            // Add activities for this day
            if (isset($tripActivities[$day])) {
                foreach ($tripActivities[$day] as $activity) {
                    $itinerary->activities()->create([
                        'title' => $activity['title'],
                        'description' => $activity['description'] ?? '',
                        'location' => $activity['location'] ?? '',
                        'start_time' => $activity['start_time'] ?? null,
                        'end_time' => $activity['end_time'] ?? null,
                        'cost' => $activity['cost'] ?? 0,
                        'category' => $activity['category'] ?? 'activity',
                        'type' => $activity['category'] ?? 'activity',
                        'is_optional' => false,
                        'is_highlight' => false,
                        'created_by' => $trip->user_id,
                    ]);
                }
            }
        }
    }

    /**
     * Process trip invites
     */
    private function processTripInvites(Trip $trip, array $invites)
    {
        foreach ($invites as $invite) {
            if (isset($invite['email']) && filter_var($invite['email'], FILTER_VALIDATE_EMAIL)) {
                $trip->members()->create([
                    'invitation_email' => $invite['email'],
                    'role' => 'member',
                    'invitation_status' => 'pending',
                ]);
            }
        }
    }

    /**
     * Clear trip planning session data
     */
    private function clearTripPlanningSession()
    {
        Session::forget([
            'selected_trip_type',
            'selected_trip_template',
            'selected_destination',
            'trip_details',
            'trip_activities',
            'trip_invites',
            'trip_base_price',
            'trip_total_price',
            'selected_optional_activities'
        ]);
    }
}