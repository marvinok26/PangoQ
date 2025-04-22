<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\Itinerary;
use App\Models\Trip;
use Illuminate\Http\Request;

class ActivityController extends Controller
{
    public function __construct()
    {
        $this->middleware('tripmember');
    }
    
    public function store(Request $request, Trip $trip, Itinerary $itinerary)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'location' => 'nullable|string|max:255',
            'start_time' => 'nullable|date_format:H:i',
            'end_time' => 'nullable|date_format:H:i|after_or_equal:start_time',
            'cost' => 'nullable|numeric|min:0',
        ]);
        
        $activity = $itinerary->activities()->create($validated);
        
        return back()->with('success', 'Activity added successfully!');
    }
    
    public function update(Request $request, Trip $trip, Itinerary $itinerary, Activity $activity)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'location' => 'nullable|string|max:255',
            'start_time' => 'nullable|date_format:H:i',
            'end_time' => 'nullable|date_format:H:i|after_or_equal:start_time',
            'cost' => 'nullable|numeric|min:0',
        ]);
        
        $activity->update($validated);
        
        return back()->with('success', 'Activity updated successfully!');
    }
    
    public function destroy(Trip $trip, Itinerary $itinerary, Activity $activity)
    {
        $activity->delete();
        
        return back()->with('success', 'Activity deleted successfully!');
    }
}