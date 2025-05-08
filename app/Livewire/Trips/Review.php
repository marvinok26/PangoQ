<?php

namespace App\Livewire\Trips;

use App\Models\TripTemplate;
use Illuminate\Support\Facades\Session;
use Livewire\Component;

class Review extends Component
{
    public $tripType;
    public $destination;
    public $tripDetails;
    public $tripActivities;
    public $invites;
    public $tripTemplate;
    public $templateActivities;
    
    public function mount()
    {
        $this->tripType = session('selected_trip_type');
        $this->destination = session('selected_destination');
        $this->tripDetails = session('trip_details', []);
        $this->tripActivities = session('trip_activities', []);
        $this->invites = session('trip_invites', []);
        
        // If pre-planned trip, get template details
        if ($this->tripType === 'pre_planned') {
            $templateId = session('selected_trip_template');
            if ($templateId) {
                $this->tripTemplate = TripTemplate::with('destination')->find($templateId);
                
                if ($this->tripTemplate) {
                    // Group activities by day
                    $activities = $this->tripTemplate->activities;
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
                    
                    $this->templateActivities = $groupedActivities;
                }
            }
        }
    }
    
    public function render()
    {
        return view('livewire.trips.review');
    }
    
    public function editTripType()
    {
        $this->dispatch('goToStep', step: 0);
    }
    
    public function editDestination()
    {
        $this->dispatch('goToStep', step: 1);
    }
    
    public function editDetails()
    {
        $this->dispatch('goToStep', step: 2);
    }
    
    public function editItinerary()
    {
        $this->dispatch('goToStep', step: 3);
    }
    
    public function editInvites()
    {
        $this->dispatch('goToStep', step: 4);
    }
}