<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\Itinerary;
use App\Models\Trip;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ItineraryController extends Controller
{
    public function __construct()
    {
        $this->middleware('tripmember');
    }
    
    /**
     * Display the itinerary planner for a trip.
     */
    public function index(Trip $trip): View
    {
        // Load itineraries with their activities, ordered by day number
        $itineraries = $trip->itineraries()
            ->orderBy('day_number')
            ->with('activities')
            ->get();
            
        return view('livewire.pages.itinerary.planner', compact('trip', 'itineraries'));
    }
    
    /**
     * Display the itinerary for a specific day.
     */
    public function show(Trip $trip, Itinerary $itinerary): View
    {
        // Load the itinerary with its activities
        $itinerary->load('activities');
        
        // Get the previous and next itineraries for navigation
        $prevItinerary = $trip->itineraries()
            ->where('day_number', '<', $itinerary->day_number)
            ->orderBy('day_number', 'desc')
            ->first();
            
        $nextItinerary = $trip->itineraries()
            ->where('day_number', '>', $itinerary->day_number)
            ->orderBy('day_number')
            ->first();
        
        return view('livewire.pages.itinerary.day', compact('trip', 'itinerary', 'prevItinerary', 'nextItinerary'));
    }
    
    /**
     * Update the specified itinerary.
     */
    public function update(Request $request, Trip $trip, Itinerary $itinerary)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);
        
        $itinerary->update($validated);
        
        return back()->with('success', 'Itinerary updated successfully!');
    }
    
    /**
     * Show the form for creating a new activity.
     */
    public function createActivity(Trip $trip, Itinerary $itinerary): View
    {
        $activityTypes = [
            'Cultural', 'Beach', 'Adventure', 'Food', 
            'Shopping', 'Nightlife', 'Relaxation', 'Sightseeing'
        ];
        
        return view('livewire.pages.itinerary.activity.create', compact('trip', 'itinerary', 'activityTypes'));
    }
    
    /**
     * Show the form for editing the specified activity.
     */
    public function editActivity(Trip $trip, Itinerary $itinerary, Activity $activity): View
    {
        // Check if the activity belongs to this itinerary
        if ($activity->itinerary_id !== $itinerary->id) {
            abort(404);
        }
        
        $activityTypes = [
            'Cultural', 'Beach', 'Adventure', 'Food', 
            'Shopping', 'Nightlife', 'Relaxation', 'Sightseeing'
        ];
        
        return view('livewire.pages.itinerary.activity.edit', compact('trip', 'itinerary', 'activity', 'activityTypes'));
    }
}