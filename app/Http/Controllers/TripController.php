<?php

namespace App\Http\Controllers;

use App\Models\Trip;
use App\Models\TripMember;
use App\Models\Itinerary;
use App\Models\Activity;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Carbon\Carbon;

class TripController extends Controller
{
    /**
     * Display a listing of the user's trips.
     */
    public function index(): View
    {
        $user = Auth::user();

        // IMPROVED APPROACH - Get trips using multiple methods
        // 1. Get trips where user is the creator
        $createdTrips = Trip::where('creator_id', $user->id)->get();

        // 2. Get trips where user is a member through the trip_members table
        $memberTrips = Trip::join('trip_members', 'trips.id', '=', 'trip_members.trip_id')
            ->where('trip_members.user_id', $user->id)
            ->where('trip_members.invitation_status', 'accepted')
            ->select('trips.*')
            ->distinct()
            ->get();

        // 3. Merge both collections and remove duplicates
        $trips = $createdTrips->merge($memberTrips)->unique('id');

        // Eager load relationships
        if ($trips->isNotEmpty()) {
            $tripIds = $trips->pluck('id')->toArray();
            $trips = Trip::whereIn('id', $tripIds)
                ->with(['members', 'savingsWallet'])
                ->orderBy('start_date', 'desc')
                ->get();
        }

        // Log trips for debugging
        Log::info('Found trips in TripController index method', [
            'user_id' => $user->id,
            'trips_count' => $trips->count(),
            'trip_ids' => $trips->pluck('id')->toArray()
        ]);

        // Instead of redirecting, just remove the flag
        if (Session::has('trip_data_not_saved') && Session::get('trip_data_not_saved')) {
            Session::forget('trip_data_not_saved');
            // Flash a message instead of redirecting
            Session::flash('success', 'Your trip has been saved successfully!');
        }

        return view('livewire.pages.trips.index', compact('trips'));
    }

    /**
     * Display the specified trip.
     */
    public function show(Trip $trip): View
    {
        $user = Auth::user();

        // Detailed logging for debugging
        logger()->info('Trip access attempt', [
            'user_id' => $user->id,
            'trip_id' => $trip->id,
            'trip_creator_id' => $trip->creator_id,
            'is_creator' => $user->id === $trip->creator_id,
            'trip_members' => $trip->members()->get()->map(function ($member) {
                return [
                    'user_id' => $member->user_id,
                    'role' => $member->role,
                    'status' => $member->invitation_status
                ];
            })
        ]);

        // Check if user is a member of this trip
        if (! Gate::allows('view', $trip)) {
            throw new AuthorizationException('You are not authorized to view this trip.');
        }

        // Eager load relationships
        $trip->load([
            'members.user',
            'savingsWallet',
            'itineraries.activities',
            'tripTemplate'
        ]);

        return view('livewire.pages.trips.show', compact('trip'));
    }

    /**
     * Show the form for editing the specified trip.
     */
    public function edit(Trip $trip): View
    {
        // Check if user is authorized to edit this trip
        if (! Gate::allows('update', $trip)) {
            throw new AuthorizationException('You are not authorized to edit this trip.');
        }

        // Eager load relationships
        $trip->load(['members.user', 'savingsWallet']);

        return view('livewire.pages.trips.edit', compact('trip'));
    }

    /**
     * Update the specified trip in storage.
     */
    public function update(Request $request, Trip $trip)
    {
        // Check if user is authorized to update this trip
        if (! Gate::allows('update', $trip)) {
            throw new AuthorizationException('You are not authorized to update this trip.');
        }

        // Validate the request
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'destination' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'budget' => 'nullable|numeric|min:' . ($trip->total_cost ?? 0),
            'status' => 'required|in:planning,active,completed',
        ], [
            'budget.min' => 'Budget cannot be less than the trip\'s total cost of $' .
                number_format($trip->total_cost ?? 0, 2)
        ]);

        // Update trip
        $trip->update($validated);

        return redirect()->route('trips.show', $trip)
            ->with('success', 'Trip updated successfully!');
    }

    /**
     * Remove the specified trip from storage.
     */
    public function destroy(Trip $trip)
    {
        // Check if user is authorized to delete this trip
        if (! Gate::allows('delete', $trip)) {
            throw new AuthorizationException('You are not authorized to delete this trip.');
        }

        // Delete trip
        $trip->delete();

        return redirect()->route('trips.index')
            ->with('success', 'Trip deleted successfully!');
    }

    /**
     * Store a newly created trip in storage.
     */
    public function store(Request $request)
    {
        // Validate request
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'destination' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'budget' => 'nullable|numeric|min:0',
        ]);

        // Create trip
        $trip = new Trip();
        $trip->creator_id = Auth::id();
        $trip->planning_type = 'self_planned';
        $trip->title = $validated['title'];
        $trip->description = $validated['description'] ?? null;
        $trip->destination = $validated['destination'];
        $trip->start_date = $validated['start_date'];
        $trip->end_date = $validated['end_date'];
        $trip->budget = $validated['budget'] ?? null;
        $trip->total_cost = $validated['budget'] ?? null;  // For self-planned trips, total cost defaults to budget
        $trip->status = 'planning';
        $trip->save();

        // Add creator as trip member
        TripMember::create([
            'trip_id' => $trip->id,
            'user_id' => Auth::id(),
            'role' => 'organizer',
            'invitation_status' => 'accepted'
        ]);

        return redirect()->route('trips.show', $trip)
            ->with('success', 'Trip created successfully!');
    }

    /**
     * Create a trip from session data (after login)
     */
    public function createFromSession()
    {
        // Check if we have session data
        if (!Session::has('trip_data_not_saved') || !Session::get('trip_data_not_saved')) {
            return redirect()->route('trips.index');
        }

        // Get trip details from session
        $tripType = Session::get('selected_trip_type');
        $destination = Session::get('selected_destination');
        $tripDetails = Session::get('trip_details', []);
        $templateId = Session::get('selected_trip_template');
        $selectedOptionalActivities = Session::get('selected_optional_activities', []);

        // Create the trip
        $trip = new Trip();
        $trip->creator_id = Auth::id();
        $trip->planning_type = $tripType ?? 'self_planned';

        // Set trip properties
        $trip->title = $tripDetails['title'] ?? ('Trip to ' . ($destination['name'] ?? 'Unknown'));
        $trip->description = $tripDetails['description'] ?? null;
        $trip->destination = $destination['name'] ?? 'Unknown';
        $trip->start_date = $tripDetails['start_date'] ?? Carbon::now()->addWeek();
        $trip->end_date = $tripDetails['end_date'] ?? Carbon::now()->addWeek(2);
        $trip->status = 'planning';

        // Handle budget and optional activities for pre-planned trips
        if ($tripType === 'pre_planned' && $templateId) {
            $trip->trip_template_id = $templateId;

            // Get base price and selected optional activities
            $basePrice = Session::get('trip_base_price', 0);
            $selectedOptionalActivities = Session::get('selected_optional_activities', []);

            // Calculate total cost
            $totalCost = $basePrice;
            foreach ($selectedOptionalActivities as $activity) {
                if (isset($activity['cost'])) {
                    $totalCost += $activity['cost'];
                }
            }

            // Set total cost and budget
            $trip->total_cost = $totalCost;
            $trip->budget = $tripDetails['budget'] ?? $totalCost;

            // Save selected optional activities as JSON
            $trip->selected_optional_activities = $selectedOptionalActivities;
        } else {
            // For self-planned trips
            $trip->budget = $tripDetails['budget'] ?? 0;
            $trip->total_cost = $trip->budget;
        }

        $trip->save();

        // Add creator as trip member
        TripMember::create([
            'trip_id' => $trip->id,
            'user_id' => Auth::id(),
            'role' => 'organizer',
            'invitation_status' => 'accepted'
        ]);

        // Create itineraries from template if applicable
        if ($tripType === 'pre_planned' && $templateId) {
            $trip->createItinerariesFromTemplate();
        } else {
            // Create custom itineraries from session activities
            $this->createCustomItineraries($trip, Session::get('trip_activities', []));
        }

        // Add trip members from invites
        $this->addTripMembers($trip, Session::get('trip_invites', []));

        // Clear session data
        $this->clearTripSessionData();

        return redirect()->route('trips.show', $trip)
            ->with('success', 'Your trip has been created!');
    }

    /**
     * Create custom itineraries from session activities
     */
    private function createCustomItineraries(Trip $trip, array $activities)
    {
        if (empty($activities)) {
            return;
        }

        foreach ($activities as $day => $dayActivities) {
            // Calculate the date for this day
            $date = Carbon::parse($trip->start_date)->addDays($day - 1);

            // Create itinerary
            $itinerary = Itinerary::create([
                'trip_id' => $trip->id,
                'title' => "Day $day: " . $trip->destination,
                'description' => "Activities for day $day in " . $trip->destination,
                'day_number' => $day,
                'date' => $date,
            ]);

            // Create activities
            foreach ($dayActivities as $activityData) {
                Activity::create([
                    'itinerary_id' => $itinerary->id,
                    'title' => $activityData['title'],
                    'description' => $activityData['description'] ?? null,
                    'location' => $activityData['location'] ?? null,
                    'start_time' => $activityData['start_time'] ?? null,
                    'end_time' => $activityData['end_time'] ?? null,
                    'cost' => $activityData['cost'] ?? 0,
                    'category' => $activityData['category'] ?? 'activity',
                ]);
            }
        }
    }

    /**
     * Add trip members from invites
     */
    private function addTripMembers(Trip $trip, array $invites)
    {
        if (empty($invites)) {
            return;
        }

        foreach ($invites as $invite) {
            TripMember::create([
                'trip_id' => $trip->id,
                'invitation_email' => $invite['email'],
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
        Session::forget([
            'selected_trip_type',
            'selected_destination',
            'selected_trip_template',
            'trip_details',
            'trip_activities',
            'trip_invites',
            'selected_optional_activities',
            'trip_data_not_saved'
        ]);
    }

    /**
     * Invite a user to the trip.
     */
    public function invite(Request $request, Trip $trip)
    {
        // Check if user is authorized to invite to this trip
        if (! Gate::allows('invite', $trip)) {
            throw new AuthorizationException('You are not authorized to invite users to this trip.');
        }

        // Validate request
        $validated = $request->validate([
            'email' => 'required|email|max:255',
        ]);

        // Create trip member invitation
        TripMember::create([
            'trip_id' => $trip->id,
            'invitation_email' => $validated['email'],
            'role' => 'member',
            'invitation_status' => 'pending'
        ]);

        return back()->with('success', 'Invitation sent successfully!');
    }

    /**
     * Display the trip planning form.
     */
    public function plan()
    {
        return view('livewire.pages.trips.create');
    }
}
