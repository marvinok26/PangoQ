<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Models\Trip;
use App\Models\Itinerary;
use App\Models\Activity;
use App\Models\TripMember;
use Carbon\Carbon;
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
        // Debug logging - added to track middleware execution
        Log::info('SaveTripAfterLogin middleware running', [
            'route' => $request->route()->getName(),
            'user_authenticated' => Auth::check(),
            'session_id' => session()->getId()
        ]);

        // Only proceed if user is authenticated
        if (Auth::check()) {
            $user = Auth::user();

            // Check if we have session data for trip planning
            $tripType = session('selected_trip_type');
            $destination = session('selected_destination');
            $tripDetails = session('trip_details');
            $tripActivities = session('trip_activities');
            $tripInvites = session('trip_invites');

            // Log session data to help debug
            Log::info('Trip session data', [
                'has_trip_type' => !empty($tripType),
                'has_destination' => !empty($destination),
                'has_trip_details' => !empty($tripDetails),
                'has_trip_activities' => !empty($tripActivities),
                'trip_data_not_saved' => session('trip_data_not_saved')
            ]);

            // Only save data if we have the minimum required fields and a flag indicating this is a new session
            $newSessionData = session('trip_data_not_saved', false);

            if ($newSessionData && $tripType && $destination && $tripDetails) {
                // Create a new Trip
                $trip = new Trip();
                $trip->creator_id = $user->id;
                $trip->planning_type = $tripType;

                // For pre-planned trips, set the template ID
                if ($tripType === 'pre_planned') {
                    $templateId = session('selected_trip_template');
                    if ($templateId) {
                        $trip->trip_template_id = $templateId;

                        // If trip_details aren't set but we have a template, we can use template data
                        if (empty($tripDetails)) {
                            $template = \App\Models\TripTemplate::find($templateId);
                            if ($template) {
                                Log::info('Using trip template for details', ['template_id' => $templateId]);

                                // Set minimum required details from template
                                $trip->title = 'Trip to ' . $template->destination->name;
                                $trip->description = $template->description;
                                $trip->destination = $template->destination->name;
                                $trip->start_date = Carbon::now()->addWeeks(2);
                                $trip->end_date = Carbon::now()->addWeeks(2)->addDays($template->duration_days);

                                // Will continue with the rest of the saving process
                            }
                        }
                    }
                }

                // Set trip details
                $trip->title = $tripDetails['title'] ?? ('Trip to ' . $destination['name']);
                $trip->description = $tripDetails['description'] ?? null;
                $trip->destination = $destination['name'];
                $trip->start_date = $tripDetails['start_date'] ?? Carbon::now()->addWeeks(2);
                $trip->end_date = $tripDetails['end_date'] ?? Carbon::now()->addWeeks(3);
                $trip->budget = $tripDetails['budget'] ?? null;
                $trip->status = 'planning';

                // Save the trip
                $trip->save();
                Log::info('Trip saved successfully', ['trip_id' => $trip->id]);

                // If pre-planned, create itineraries from template
                if ($tripType === 'pre_planned' && isset($trip->trip_template_id)) {
                    $trip->createItinerariesFromTemplate();
                }
                // Otherwise create itineraries from session data
                else if ($tripActivities) {
                    $this->createItinerariesFromSessionData($trip, $tripActivities);
                }

                // Add invites if any
                if ($tripInvites) {
                    $this->processInvites($trip, $tripInvites);
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

                // Redirect to the trip dashboard or detail page after saving
                if ($request->route()->getName() === 'login') {
                    session()->flash('success', 'Your trip planning data has been saved!');
                    return redirect()->route('trips.index');
                }
            } else {
                // If no session data, remove the flag
                session()->forget('trip_data_not_saved');
            }
        }

        return $next($request);
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
                $activity->save();
            }
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
            'trip_data_not_saved'
        ]);
    }
}
