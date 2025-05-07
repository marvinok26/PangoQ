<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ActivityController extends Controller
{
    /**
     * Display a listing of activities for a trip.
     */
    public function index(Request $request)
    {
        $tripId = $request->input('trip_id');
        $dayNumber = $request->input('day');
        
        $activities = Activity::whereHas('itinerary', function($query) use ($tripId, $dayNumber) {
            $query->where('trip_id', $tripId);
            
            if ($dayNumber) {
                $query->where('day_number', $dayNumber);
            }
        })->get();
        
        return response()->json(['activities' => $activities]);
    }

    /**
     * Store a newly created activity in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'itinerary_id' => 'required|exists:itineraries,id',
            'title' => 'required|string|max:255',
            'location' => 'nullable|string|max:255',
            'start_time' => 'nullable|date_format:H:i',
            'end_time' => 'nullable|date_format:H:i|after:start_time',
            'cost' => 'nullable|numeric|min:0',
        ]);
        
        $activity = Activity::create($request->all());
        
        return response()->json(['activity' => $activity, 'message' => 'Activity created successfully']);
    }

    /**
     * Get activity suggestion by ID.
     */
    public function getSuggestion($id)
    {
        // This would typically fetch from a database, but for this example,
        // we'll return some sample data
        $suggestions = [
            1 => [
                'name' => 'Kecak Fire Dance at Uluwatu',
                'location' => 'Uluwatu Temple',
                'cost' => 15,
                'category' => 'cultural',
                'description' => 'Experience the mesmerizing traditional Balinese Kecak dance performance at sunset, with the majestic Uluwatu Temple as the backdrop.',
            ],
            2 => [
                'name' => 'Seafood Dinner at Jimbaran Bay',
                'location' => 'Jimbaran Beach',
                'cost' => 30,
                'category' => 'food',
                'description' => 'Enjoy fresh seafood grilled to perfection right on the beach, with your feet in the sand as the sun sets over the ocean.',
            ],
        ];
        
        if (isset($suggestions[$id])) {
            return response()->json(['success' => true, 'data' => $suggestions[$id]]);
        }
        
        return response()->json(['success' => false, 'message' => 'Suggestion not found'], 404);
    }
    
    /**
     * Update the specified activity in storage.
     */
    public function update(Request $request, Activity $activity)
    {
        $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'location' => 'nullable|string|max:255',
            'start_time' => 'nullable|date_format:H:i',
            'end_time' => 'nullable|date_format:H:i|after:start_time',
            'cost' => 'nullable|numeric|min:0',
        ]);
        
        $activity->update($request->all());
        
        return response()->json(['activity' => $activity, 'message' => 'Activity updated successfully']);
    }

    /**
     * Remove the specified activity from storage.
     */
    public function destroy(Activity $activity)
    {
        $activity->delete();
        
        return response()->json(['message' => 'Activity deleted successfully']);
    }
}