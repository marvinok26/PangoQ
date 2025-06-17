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
use App\Models\TripInvitation;
use Carbon\Carbon;
use Illuminate\Support\Str;

class HandleTripSessionData
{
    /**
     * Handle an incoming request and process trip session data.
     */
    public function handle(Request $request, Closure $next)
    {
        // Check if we need to restore from Alpine.js localStorage
        if (Auth::check() && !$this->hasMinimumTripData() && $this->shouldRestoreFromStorage($request)) {
            $this->flagForStorageRestoration();
        }

        // Only process if user is authenticated and has trip data to save
        if (Auth::check() && (Session::get('trip_data_not_saved') || Session::get('trip_creation_pending'))) {
            try {
                $trip = $this->createTripFromSession();
                Session::forget(['trip_data_not_saved', 'trip_creation_pending']);
                
                // Clear trip planning session data after saving
                $this->clearTripPlanningSession();
                
                Log::info('Trip created successfully from session data', [
                    'user_id' => Auth::id(),
                    'trip_id' => $trip->id
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
     * Check if we have minimum trip data in session
     */
    private function hasMinimumTripData(): bool
    {
        return Session::has('selected_trip_type') && 
               (Session::has('selected_destination') || Session::has('selected_trip_template'));
    }

    /**
     * Check if we should restore from localStorage
     */
    private function shouldRestoreFromStorage(Request $request): bool
    {
        return $request->has('_restore_from_storage') || 
               $request->header('X-Has-Trip-Data') === 'true' ||
               Session::get('restore_trip_data_needed', false);
    }

    /**
     * Flag that we need to restore data from localStorage
     */
    private function flagForStorageRestoration(): void
    {
        Session::put('restore_trip_data_needed', true);
        Log::info('Flagged for trip data restoration from localStorage');
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
        $destinationModel = $this->findOrCreateDestination($destination);
        if (!$destinationModel) {
            throw new \Exception('Destination not found');
        }

        // Prepare trip data with proper type casting
        $tripData = [
            'creator_id' => Auth::id(),
            'title' => is_string($tripDetails['title'] ?? null) ? $tripDetails['title'] : 'Trip to ' . $destinationModel->name,
            'destination' => is_string($destinationModel->name) ? $destinationModel->name : 'Unknown Destination',
            'start_date' => $tripDetails['start_date'] ?? now()->addWeeks(2)->format('Y-m-d'),
            'end_date' => $tripDetails['end_date'] ?? now()->addWeeks(3)->format('Y-m-d'),
            'budget' => is_numeric($tripDetails['budget'] ?? 0) ? (float)$tripDetails['budget'] : 0.0,
            'total_cost' => is_numeric($tripDetails['total_cost'] ?? Session::get('trip_total_price', 0)) ? (float)($tripDetails['total_cost'] ?? Session::get('trip_total_price', 0)) : 0.0,
            'planning_type' => is_string($tripType) ? $tripType : 'self_planned',
            'status' => 'planning',
        ];

        // Handle pre-planned trips
        if ($tripType === 'pre_planned') {
            $templateData = Session::get('selected_trip_template');
            $templateId = null;
            
            // Handle different template data formats
            if (is_array($templateData) && isset($templateData['id'])) {
                $templateId = $templateData['id'];
            } elseif (is_numeric($templateData)) {
                $templateId = $templateData;
            }
            
            if ($templateId) {
                $template = TripTemplate::find($templateId);
                if ($template) {
                    $tripData['trip_template_id'] = $template->id;
                    $tripData['total_cost'] = Session::get('trip_total_price', $template->base_price);
                    $tripData['budget'] = max($tripData['budget'], $tripData['total_cost']);
                }
            }
        }

        // Create the trip
        $trip = Trip::create($tripData);

        // Store selected optional activities in trip if pre-planned
        if ($tripType === 'pre_planned' && Session::has('selected_optional_activities')) {
            $trip->update([
                'selected_optional_activities' => json_encode(Session::get('selected_optional_activities'))
            ]);
        }

        // Create itineraries and activities
        $this->createTripItineraries($trip, $tripType);

        // Send invites
        if (!empty($tripInvites)) {
            $this->processTripInvites($trip, $tripInvites);
        }

        // Store trip ID in session for redirect
        Session::put('newly_created_trip_id', $trip->id);
        Session::flash('success', 'Your trip "' . $trip->title . '" has been created successfully!');

        return $trip;
    }

    /**
     * Find or create destination
     */
    private function findOrCreateDestination($destination)
    {
        $destinationModel = null;
        
        try {
            if (is_array($destination) && isset($destination['id'])) {
                $destinationModel = Destination::find($destination['id']);
            } elseif (is_array($destination) && isset($destination['name'])) {
                $destinationModel = Destination::where('name', $destination['name'])->first();
                
                // If not found, create it
                if (!$destinationModel && !empty($destination['name'])) {
                    $destinationModel = Destination::create([
                        'name' => $destination['name'],
                        'country' => $destination['country'] ?? 'Unknown',
                        'description' => $destination['description'] ?? '',
                        'is_active' => true
                    ]);
                }
            } elseif (is_string($destination) && !empty($destination)) {
                $destinationModel = Destination::where('name', $destination)->first();
                
                // If not found, create it
                if (!$destinationModel) {
                    $destinationModel = Destination::create([
                        'name' => $destination,
                        'country' => 'Unknown',
                        'description' => '',
                        'is_active' => true
                    ]);
                }
            }
        } catch (\Exception $e) {
            Log::error('Error finding/creating destination', [
                'destination' => $destination,
                'error' => $e->getMessage()
            ]);
        }

        return $destinationModel;
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
                        'is_optional' => false,
                        'is_highlight' => $templateActivity->is_highlight,
                        'created_by' => $trip->creator_id,
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
                            'is_optional' => true,
                            'is_highlight' => $optionalActivity->is_highlight,
                            'created_by' => $trip->creator_id,
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

            // Add activities for this day if they exist
            if (isset($tripActivities[$day]) && is_array($tripActivities[$day])) {
                foreach ($tripActivities[$day] as $activity) {
                    if (!is_array($activity)) continue;

                    $itinerary->activities()->create([
                        'title' => $activity['title'] ?? 'Activity',
                        'description' => $activity['description'] ?? '',
                        'location' => $activity['location'] ?? '',
                        'start_time' => $activity['start_time'] ?? null,
                        'end_time' => $activity['end_time'] ?? null,
                        'cost' => is_numeric($activity['cost'] ?? null) ? $activity['cost'] : 0,
                        'category' => $activity['category'] ?? 'activity',
                        'is_optional' => false,
                        'is_highlight' => false,
                        'created_by' => $trip->creator_id,
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
        if (!is_array($invites)) return;

        foreach ($invites as $invite) {
            if (!is_array($invite) || !isset($invite['email'])) continue;
            
            if (filter_var($invite['email'], FILTER_VALIDATE_EMAIL)) {
                try {
                    // Create trip invitation instead of trip member directly
                    TripInvitation::create([
                        'trip_id' => $trip->id,
                        'email' => $invite['email'],
                        'name' => $invite['name'] ?? null,
                        'message' => $invite['message'] ?? null,
                        'invited_by' => $trip->creator_id,
                        'status' => 'pending',
                        'token' => Str::random(32),
                    ]);
                } catch (\Exception $e) {
                    Log::warning('Failed to create trip invitation', [
                        'trip_id' => $trip->id,
                        'email' => $invite['email'],
                        'error' => $e->getMessage()
                    ]);
                }
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
            'selected_optional_activities',
            'restore_trip_data_needed'
        ]);

        // Keep recent searches for future use
    }
}