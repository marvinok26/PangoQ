<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\Itinerary;
use App\Models\Trip;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ItineraryController extends Controller
{
    /**
     * Display the full itinerary for a trip.
     */
    public function index(Trip $trip): View
    {
        // Load all necessary relationships for the comprehensive view
        $trip->load([
            'creator',
            'members.user',
            'tripTemplate',
            'savingsWallet',
            'itineraries' => function ($query) {
                $query->orderBy('day_number')
                      ->with(['activities' => function ($activityQuery) {
                          $activityQuery->orderBy('start_time');
                      }]);
            }
        ]);
        
        // Get itineraries separately for easy access in the view
        $itineraries = $trip->itineraries;
        
        return view('livewire.pages.trips.itinerary', compact('trip', 'itineraries'));
    }
    
    /**
     * Show the form for editing the trip itinerary.
     */
    public function edit(Trip $trip): View
    {
        // Load itineraries with their activities for editing
        $trip->load([
            'itineraries' => function ($query) {
                $query->orderBy('day_number')
                      ->with('activities');
            }
        ]);
        
        return view('livewire.pages.trips.itinerary-edit', compact('trip'));
    }
    
    /**
     * Display the itinerary planner for a trip.
     */
    public function planner(Trip $trip): View
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
        // Ensure the itinerary belongs to this trip
        if ($itinerary->trip_id !== $trip->id) {
            abort(404);
        }
        
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
        // Ensure the itinerary belongs to this trip
        if ($itinerary->trip_id !== $trip->id) {
            abort(404);
        }
        
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
        // Ensure the itinerary belongs to this trip
        if ($itinerary->trip_id !== $trip->id) {
            abort(404);
        }
        
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
        // Check if the activity belongs to this itinerary and trip
        if ($activity->itinerary_id !== $itinerary->id || $itinerary->trip_id !== $trip->id) {
            abort(404);
        }
        
        $activityTypes = [
            'Cultural', 'Beach', 'Adventure', 'Food', 
            'Shopping', 'Nightlife', 'Relaxation', 'Sightseeing'
        ];
        
        return view('livewire.pages.itinerary.activity.edit', compact('trip', 'itinerary', 'activity', 'activityTypes'));
    }
}