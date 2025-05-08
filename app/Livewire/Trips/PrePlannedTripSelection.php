<?php

namespace App\Livewire\Trips;

use App\Models\Destination;
use App\Models\TripTemplate;
use Illuminate\Support\Facades\Session;
use Livewire\Component;

class PrePlannedTripSelection extends Component
{
    public $destinations = [];
    public $selectedDestination = null;
    public $tripTemplates = [];
    public $selectedTemplate = null;
    public $showTemplateDetails = false;
    public $templateActivities = [];
    
    public function mount()
    {
        $this->destinations = Destination::has('tripTemplates')->get();
    }
    
    public function render()
    {
        return view('livewire.trips.pre-planned-trip-selection');
    }
    
    public function selectDestination($destinationId)
    {
        $this->selectedDestination = Destination::find($destinationId);
        $this->tripTemplates = TripTemplate::where('destination_id', $destinationId)
            ->with('destination')
            ->get();
        
        // Reset template selection
        $this->selectedTemplate = null;
        $this->showTemplateDetails = false;
        $this->templateActivities = [];
    }
    
    public function viewTemplateDetails($templateId)
    {
        $this->selectedTemplate = TripTemplate::with('activities', 'destination')->find($templateId);
        $this->showTemplateDetails = true;
        
        // Group activities by day
        $activities = $this->selectedTemplate->activities;
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
    
    public function selectTripTemplate()
    {
        if (!$this->selectedTemplate) {
            return;
        }
        
        // Store template selection in session
        Session::put('selected_trip_template', $this->selectedTemplate->id);
        Session::put('selected_destination', [
            'id' => $this->selectedDestination->id,
            'name' => $this->selectedDestination->name,
            'country' => $this->selectedDestination->country,
            'city' => $this->selectedDestination->city,
        ]);
        
        // Dispatch event to parent component
        $this->dispatch('tripTemplateSelected', tripTemplateId: $this->selectedTemplate->id);
    }
    
    public function backToTemplates()
    {
        $this->showTemplateDetails = false;
    }
    
    public function backToDestinations()
    {
        $this->selectedDestination = null;
        $this->tripTemplates = [];
    }
}