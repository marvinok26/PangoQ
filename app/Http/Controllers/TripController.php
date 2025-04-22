<?php

namespace App\Http\Controllers;

use App\Models\Trip;
use App\Models\Destination;
use App\Services\TripService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class TripController extends Controller
{
    /**
     * The trip service instance.
     */
    protected $tripService;

    /**
     * Create a new controller instance.
     */
    public function __construct(TripService $tripService)
    {
        $this->tripService = $tripService;
        
        // Add except for create so anyone can access the trip creation form
        // $this->middleware('auth')->except(['create']);
        
        // Only the trip members can access these routes
        // $this->middleware('tripmember')->only(['show', 'edit', 'update', 'destroy']);
    }

    /**
     * Display a listing of the trips.
     */
    public function index(): View
    {
        $trips = Auth::check() 
            ? Auth::user()->trips()->latest()->get() 
            : collect();
        
        return view('livewire.pages.trips.index', compact('trips'));
    }

    /**
     * Show the form for creating a new trip.
     */
    public function create(): View
    {
        $destinations = Destination::all();
        return view('livewire.pages.trips.create', compact('destinations'));
    }

    /**
     * Store a newly created trip in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'destination' => 'required|string|max:255',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after_or_equal:start_date',
            'budget' => 'nullable|numeric|min:0',
        ]);
        
        $trip = $this->tripService->createTrip($validated, Auth::id());
        
        return redirect()->route('trips.show', $trip)
            ->with('success', 'Trip created successfully!');
    }

    /**
     * Display the specified trip.
     */
    public function show(Trip $trip): View
    {
        $trip->load(['itineraries.activities', 'members.user', 'savingsWallet']);
        
        return view('livewire.pages.trips.show', compact('trip'));
    }

    /**
     * Show the form for editing the specified trip.
     */
    public function edit(Trip $trip): View
    {
        return view('livewire.pages.trips.edit', compact('trip'));
    }

    /**
     * Update the specified trip in storage.
     */
    public function update(Request $request, Trip $trip)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'destination' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'budget' => 'nullable|numeric|min:0',
            'status' => 'sometimes|in:planning,active,completed,cancelled',
        ]);
        
        $trip->update($validated);
        
        if (isset($validated['status'])) {
            $this->tripService->updateTripStatus($trip, $validated['status']);
        }
        
        return redirect()->route('trips.show', $trip)
            ->with('success', 'Trip updated successfully!');
    }

    /**
     * Remove the specified trip from storage.
     */
    public function destroy(Trip $trip)
    {
        // Only allow the creator to delete the trip
        if (Auth::id() !== $trip->creator_id) {
            return back()->with('error', 'You do not have permission to delete this trip.');
        }
        
        $trip->delete();
        
        return redirect()->route('trips.index')
            ->with('success', 'Trip deleted successfully!');
    }

    /**
     * Invite members to the trip.
     */
    public function invite(Request $request, Trip $trip)
    {
        $validated = $request->validate([
            'emails' => 'required|array',
            'emails.*' => 'required|email',
        ]);
        
        $this->tripService->inviteMembers($trip, $validated['emails']);
        
        return back()->with('success', 'Invitations sent successfully!');
    }
}