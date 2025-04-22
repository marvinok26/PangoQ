<?php

namespace App\Http\Controllers;

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
    
    public function index(Trip $trip): View
    {
        $itineraries = $trip->itineraries()
            ->orderBy('day_number')
            ->with('activities')
            ->get();
            
        return view('livewire.pages.itinerary.planner', compact('trip', 'itineraries'));
    }
    
    public function show(Trip $trip, Itinerary $itinerary): View
    {
        $itinerary->load('activities');
        
        return view('livewire.pages.itinerary.day', compact('trip', 'itinerary'));
    }
    
    public function update(Request $request, Trip $trip, Itinerary $itinerary)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);
        
        $itinerary->update($validated);
        
        return back()->with('success', 'Itinerary updated successfully!');
    }
}