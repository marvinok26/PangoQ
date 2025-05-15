<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\Trip;
use App\Models\Itinerary;
use App\Models\Activity;
use App\Models\TripMember;
use Carbon\Carbon;

class AuthenticatedSessionController extends Controller
{
    /**
     * Handle an incoming authentication request.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ]);

        $credentials = $request->only('email', 'password');
        $remember = $request->boolean('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();

            // Log successful login
            Log::info('User logged in successfully', ['email' => $request->email]);
            
            // Save trip data if available
            $this->saveSessionTripData();
            
            return redirect()->intended(route('dashboard'));
        }

        // Log failed login attempt
        Log::info('Failed login attempt', ['email' => $request->email]);

        return back()->withErrors([
            'email' => __('auth.failed'),
        ])->withInput($request->only('email'));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
    
    /**
     * Save trip data from session after successful authentication
     */
    protected function saveSessionTripData()
    {
        // Log that we're attempting to save trip data
        Log::info('Attempting to save trip data after login');
        
        $user = Auth::user();
        
        // Check if we have session data for trip planning
        $tripType = session('selected_trip_type');
        $destination = session('selected_destination');
        $tripDetails = session('trip_details');
        $tripActivities = session('trip_activities');
        $tripInvites = session('trip_invites');
        
        // Log the session data
        Log::info('Trip session data after login', [
            'has_trip_type' => !empty($tripType),
            'has_destination' => !empty($destination),
            'has_trip_details' => !empty($tripDetails),
            'trip_data_not_saved' => session('trip_data_not_saved')
        ]);
        
        // Only save data if we have the minimum required fields and a flag indicating this is a new session
        $newSessionData = session('trip_data_not_saved', false);
        
        if ($newSessionData && $tripType && $destination) {
            try {
                // For pre-planned trips without details, create minimal details
                if ($tripType === 'pre_planned' && empty($tripDetails)) {
                    $tripDetails = [
                        'title' => 'Trip to ' . ($destination['name'] ?? 'Unknown'),
                        'start_date' => Carbon::now()->addWeeks(2)->format('Y-m-d'),
                        'end_date' => Carbon::now()->addWeeks(3)->format('Y-m-d')
                    ];
                    
                    Log::info('Created minimal trip details for pre-planned trip', $tripDetails);
                }
                
                // Now save the trip if we have the required details
                if ($tripDetails) {
                    // Create a new Trip
                    $trip = new Trip();
                    $trip->creator_id = $user->id;
                    $trip->planning_type = $tripType;
                    
                    // For pre-planned trips, set the template ID
                    if ($tripType === 'pre_planned') {
                        $templateId = session('selected_trip_template');
                        if ($templateId) {
                            $trip->trip_template_id = $templateId;
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
                        // If you have a method on the Trip model
                        if (method_exists($trip, 'createItinerariesFromTemplate')) {
                            $trip->createItinerariesFromTemplate();
                            Log::info('Created itineraries from template');
                        }
                    } 
                    // Otherwise create itineraries from session data
                    else if ($tripActivities) {
                        $this->createItinerariesFromSessionData($trip, $tripActivities);
                        Log::info('Created itineraries from session data');
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
                }
            } catch (\Exception $e) {
                Log::error('Failed to save trip data after login', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
            }
        } else {
            // Log why we're not saving trip data
            if (!$newSessionData) {
                Log::info('No trip_data_not_saved flag in session');
            } else {
                Log::info('Missing required trip fields', [
                    'has_trip_type' => !empty($tripType),
                    'has_destination' => !empty($destination),
                    'has_trip_details' => !empty($tripDetails)
                ]);
            }
            
            // If no session data, remove the flag
            session()->forget('trip_data_not_saved');
        }
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