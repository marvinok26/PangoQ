<?php

namespace App\Http\Controllers;

use App\Models\Trip;
use App\Models\TripTemplate;
use App\Models\Destination;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TripController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        $trips = Trip::where('creator_id', $user->id)
            ->orWhereHas('members', function($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->with(['savingsWallet', 'creator', 'members', 'tripTemplate'])
            ->orderBy('start_date', 'asc')
            ->get();
        
        return view('trips.index', compact('trips'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('trips.create');
    }

    /**
     * Store a new resource in storage.
     */
    public function store(Request $request)
    {
        // Validation
        $validated = $request->validate([
            'planning_type' => 'required|in:self_planned,pre_planned',
            'title' => 'required|string|max:255',
            'destination' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'budget' => 'required|numeric|min:0',
            'trip_template_id' => 'nullable|exists:trip_templates,id',
        ]);
        
        // Create trip
        $trip = Trip::create([
            'creator_id' => Auth::id(),
            'planning_type' => $validated['planning_type'],
            'title' => $validated['title'],
            'description' => $request->description ?? null,
            'destination' => $validated['destination'],
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'budget' => $validated['budget'],
            'status' => 'planning',
            'trip_template_id' => $validated['trip_template_id'] ?? null,
        ]);
        
        // For pre-planned trips, create itineraries from template
        if ($trip->planning_type === 'pre_planned' && $trip->trip_template_id) {
            $trip->createItinerariesFromTemplate();
        } 
        // Create default itinerary if none exists for self-planned trips
        else if ($trip->planning_type === 'self_planned') {
            $startDate = $trip->start_date;
            $endDate = $trip->end_date;
            $dayCount = $startDate->diffInDays($endDate) + 1; // Include start and end day
            
            for ($day = 1; $day <= $dayCount; $day++) {
                $date = clone $startDate;
                $date->addDays($day - 1);
                
                $trip->itineraries()->create([
                    'title' => "Day {$day}: {$trip->destination}",
                    'description' => "Day {$day} activities in {$trip->destination}",
                    'day_number' => $day,
                    'date' => $date,
                ]);
            }
        }
        
        // Create savings wallet
        $trip->savingsWallet()->create([
            'name' => 'Trip to ' . $trip->destination,
            'target_amount' => $trip->budget,
            'current_amount' => 0,
            'target_date' => $trip->end_date,
            'contribution_frequency' => 'weekly'
        ]);
        
        return redirect()->route('trips.show', $trip)->with('success', 'Trip created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Trip $trip)
    {
        // Check if user is authorized to view the trip
        if (!$this->userCanAccessTrip($trip)) {
            return redirect()->route('trips.index')->with('error', 'You do not have permission to view this trip.');
        }
        
        $trip->load(['creator', 'members', 'itineraries.activities', 'savingsWallet', 'tripTemplate']);
        
        return view('trips.show', compact('trip'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Trip $trip)
    {
        // Check if user is authorized to edit the trip
        if (!$this->userCanAccessTrip($trip)) {
            return redirect()->route('trips.index')->with('error', 'You do not have permission to edit this trip.');
        }
        
        $trip->load(['creator', 'members', 'itineraries.activities', 'savingsWallet', 'tripTemplate']);
        
        return view('trips.edit', compact('trip'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Trip $trip)
    {
        // Check if user is authorized to update the trip
        if (!$this->userCanAccessTrip($trip)) {
            return redirect()->route('trips.index')->with('error', 'You do not have permission to update this trip.');
        }
        
        // Validation
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'budget' => 'required|numeric|min:0',
            'status' => 'nullable|in:planning,booked,in_progress,completed,cancelled',
        ]);
        
        // Update trip
        $trip->update($validated);
        
        // Update savings wallet target
        if ($trip->savingsWallet) {
            $trip->savingsWallet->update([
                'target_amount' => $validated['budget'],
                'target_date' => $validated['end_date'],
            ]);
        }
        
        return redirect()->route('trips.show', $trip)->with('success', 'Trip updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Trip $trip)
    {
        // Check if user is authorized to delete the trip
        if (Auth::id() !== $trip->creator_id) {
            return redirect()->route('trips.index')->with('error', 'You do not have permission to delete this trip.');
        }
        
        $trip->delete();
        
        return redirect()->route('trips.index')->with('success', 'Trip deleted successfully!');
    }
    
    /**
     * Browse pre-planned trip templates.
     */
    public function browseTemplates()
    {
        $destinations = Destination::has('tripTemplates')->get();
        $featuredTemplates = TripTemplate::where('is_featured', true)
            ->with('destination')
            ->take(6)
            ->get();
        
        return view('trips.browse-templates', compact('destinations', 'featuredTemplates'));
    }
    
    /**
     * Show template details.
     */
    public function showTemplate(TripTemplate $template)
    {
        $template->load(['destination', 'activities']);
        
        // Group activities by day
        $activities = $template->activities;
        $groupedActivities = [];
        
        foreach ($activities as $activity) {
            $groupedActivities[$activity->day_number][] = $activity;
        }
        
        // Sort activities by start_time for each day
        foreach ($groupedActivities as $day => $dayActivities) {
            usort($dayActivities, function($a, $b) {
                return $a->start_time <=> $b->start_time;
            });
            
            $groupedActivities[$day] = $dayActivities;
        }
        
        return view('trips.template-details', compact('template', 'groupedActivities'));
    }
    
    /**
     * Check if user can access a trip.
     */
    private function userCanAccessTrip(Trip $trip)
    {
        $user = Auth::user();
        
        // Trip creator can access
        if ($trip->creator_id === $user->id) {
            return true;
        }
        
        // Trip members can access
        $isMember = $trip->members()->where('user_id', $user->id)->exists();
        if ($isMember) {
            return true;
        }
        
        return false;
    }
}