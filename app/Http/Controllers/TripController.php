<?php

namespace App\Http\Controllers;

use App\Models\Trip;
use App\Models\TripMember;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class TripController extends Controller
{
    /**
     * Display a listing of the user's trips.
     */
    public function index(): View
    {
        $user = Auth::user();

        // Get trips where the user is a member
        $trips = Trip::whereHas('members', function($query) use ($user) {
            $query->where('user_id', $user->id);
        })
        ->with(['members', 'savingsWallet'])
        ->orderBy('start_date', 'desc')
        ->get();

        return view('livewire.pages.trips.index', compact('trips'));
    }

    /**
     * Display the specified trip.
     */
    public function show(Trip $trip): View
    {
        // Check if user is a member of this trip
        if (! Gate::allows('view', $trip)) {
            throw new AuthorizationException('You are not authorized to view this trip.');
        }

        // Eager load relationships
        $trip->load([
            'members.user', 
            'savingsWallet', 
            'itineraries.activities'
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
            'budget' => 'nullable|numeric|min:0',
            'status' => 'required|in:planning,active,completed',
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
}