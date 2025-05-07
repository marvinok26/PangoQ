<?php

namespace App\Livewire\Trips;

use Livewire\Component;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use App\Models\Trip;
use App\Models\Itinerary;
use App\Models\Activity;
use App\Models\TripMember;
use Carbon\Carbon;

class Review extends Component
{
    public $destination;
    public $destinationInfo;
    public $tripTitle;
    public $startDate;
    public $endDate;
    public $budget;
    public $travelers;
    public $tripType;
    public $tripPace;
    public $activityInterests = [];
    public $accommodationType;
    public $dayActivities = [];
    public $invitedFriends = [];
    public $totalDays;
    public $showBudget = true; // For toggling budget visibility
    
    public function mount()
    {
        // Get all trip data from session
        $selectedDestination = session('selected_destination');
        $tripDetails = session('trip_details');
        $tripActivities = session('trip_activities');
        $tripInvites = session('trip_invites');
        
        if ($selectedDestination) {
            $this->destination = $selectedDestination['name'] ?? '';
            $this->destinationInfo = $selectedDestination;
        }
        
        if ($tripDetails) {
            $this->tripTitle = $tripDetails['title'] ?? '';
            $this->startDate = $tripDetails['start_date'] ?? '';
            $this->endDate = $tripDetails['end_date'] ?? '';
            $this->budget = $tripDetails['budget'] ?? '';
            $this->travelers = $tripDetails['travelers'] ?? 4;
            $this->tripType = $tripDetails['trip_type'] ?? '';
            $this->tripPace = $tripDetails['trip_pace'] ?? 5;
            $this->activityInterests = $tripDetails['activity_interests'] ?? [];
            $this->accommodationType = $tripDetails['accommodation_type'] ?? '';
            
            // Calculate total days
            if ($this->startDate && $this->endDate) {
                $start = Carbon::parse($this->startDate);
                $end = Carbon::parse($this->endDate);
                $this->totalDays = $start->diffInDays($end) + 1; // Include start & end days
            }
        }
        
        if ($tripActivities) {
            $this->dayActivities = $tripActivities;
        }
        
        if ($tripInvites) {
            $this->invitedFriends = $tripInvites;
        }
    }

    public function render()
    {
        return view('livewire.trips.review');
    }
    
    public function saveTrip()
    {
        // In a real application, you would save the trip to the database
        // For now, we'll just simulate successful creation
        
        // Gather all trip data
        $tripData = [
            'destination' => $this->destination,
            'title' => $this->tripTitle,
            'start_date' => $this->startDate,
            'end_date' => $this->endDate,
            'budget' => $this->budget,
            'travelers' => $this->travelers,
            'trip_type' => $this->tripType,
            'trip_pace' => $this->tripPace,
            'activity_interests' => $this->activityInterests,
            'accommodation_type' => $this->accommodationType,
            'activities' => $this->dayActivities,
            'invites' => $this->invitedFriends
        ];
        
        // Save the trip data to session (for demo purposes)
        session(['saved_trip' => $tripData]);
        
        // In a real app, you would typically redirect to the trip view page
        // For now, redirect to dashboard with success message
        session()->flash('message', 'Trip created successfully!');
        return redirect()->route('dashboard');
    }
}