<?php

namespace App\Livewire\Trips;

use App\Models\Destination;
use App\Models\TripTemplate;
use App\Models\TemplateActivity;
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
    public $templateHighlights = [];
    public $optionalActivities = [];
    
    // New properties for optional activities selection
    public $selectedOptionalActivities = [];
    public $totalPrice = 0;

    public function mount()
    {
        $this->destinations = Destination::has('tripTemplates')->get();
        
        // Check if there's a pre-selected destination from the welcome page form
        $sessionDestination = Session::get('selected_destination');
        if ($sessionDestination && is_string($sessionDestination)) {
            // If it's a string (from welcome form), find the destination by name
            $destination = Destination::where('name', 'LIKE', "%{$sessionDestination}%")->first();
            if ($destination) {
                $this->selectDestination($destination->id);
            }
        } elseif ($sessionDestination && is_array($sessionDestination) && isset($sessionDestination['id'])) {
            // If it's already an array with ID (from existing flow)
            $this->selectDestination($sessionDestination['id']);
        }
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
        $this->templateHighlights = [];
        $this->selectedOptionalActivities = [];
        $this->totalPrice = 0;
    }

    protected function getListeners()
    {
        return [
            'viewTemplate' => 'handleViewTemplate',
        ];
    }

    public function handleViewTemplate($data)
    {
        \Illuminate\Support\Facades\Log::info('handleViewTemplate called with data: ', $data);
        $this->viewTemplateDetails($data['templateId']);
    }

    public function viewTemplateDetails($templateId)
    {
        // Debug information
        \Illuminate\Support\Facades\Log::info('viewTemplateDetails called with ID: ' . $templateId);

        $this->selectedTemplate = TripTemplate::with(['activities', 'destination'])->find($templateId);
        $this->showTemplateDetails = true;

        // Reset selected optional activities
        $this->selectedOptionalActivities = [];
        
        // Set initial total price to base price
        $this->totalPrice = $this->selectedTemplate->base_price;

        // Parse the highlights field if it exists
        if ($this->selectedTemplate->highlights) {
            // Check if highlights is already an array
            $this->templateHighlights = is_array($this->selectedTemplate->highlights) 
                ? $this->selectedTemplate->highlights 
                : json_decode($this->selectedTemplate->highlights, true) ?? [];
        } else {
            $this->templateHighlights = [];
        }

        // Group activities by day and separate optional activities
        $activities = $this->selectedTemplate->activities;
        $groupedActivities = [];
        $optionalActivities = [];

        foreach ($activities as $activity) {
            if ($activity->is_optional) {
                $optionalActivities[] = $activity;
            } else {
                $groupedActivities[$activity->day_number][] = $activity;
            }
        }

        // Sort activities by start_time for each day
        foreach ($groupedActivities as $day => $dayActivities) {
            usort($dayActivities, function ($a, $b) {
                return $a->start_time <=> $b->start_time;
            });

            $groupedActivities[$day] = $dayActivities;
        }

        $this->templateActivities = $groupedActivities;
        $this->optionalActivities = $optionalActivities;
    }

    // Toggle optional activity selection
    public function toggleOptionalActivity($activityId)
    {
        if (isset($this->selectedOptionalActivities[$activityId])) {
            // If already selected, remove it
            $activityCost = $this->selectedOptionalActivities[$activityId]['cost'];
            $this->totalPrice -= $activityCost;
            unset($this->selectedOptionalActivities[$activityId]);
        } else {
            // If not selected, add it
            $activity = TemplateActivity::find($activityId);
            if ($activity) {
                $this->selectedOptionalActivities[$activityId] = [
                    'id' => $activity->id,
                    'title' => $activity->title,
                    'cost' => $activity->cost
                ];
                $this->totalPrice += $activity->cost;
            }
        }
        
        // Debug information
        \Illuminate\Support\Facades\Log::info('Optional activity toggled. New total price: ' . $this->totalPrice);
        \Illuminate\Support\Facades\Log::info('Selected optional activities: ', $this->selectedOptionalActivities);
    }

    public function selectTripTemplate()
    {
        if (!$this->selectedTemplate) {
            return;
        }

        // Store template selection in session
        Session::put('selected_trip_template', $this->selectedTemplate->id);
        Session::put('selected_trip_type', 'pre_planned');
        Session::put('selected_destination', [
            'id' => $this->selectedDestination->id,
            'name' => $this->selectedDestination->name,
            'country' => $this->selectedDestination->country,
            'city' => $this->selectedDestination->city,
        ]);

        // Store price information and selected optional activities in session
        Session::put('trip_base_price', $this->selectedTemplate->base_price);
        Session::put('selected_optional_activities', $this->selectedOptionalActivities);
        Session::put('trip_total_price', $this->totalPrice);
        
        // Update trip details with total price
        $tripDetails = Session::get('trip_details', []);
        $tripDetails['total_cost'] = $this->totalPrice;
        $tripDetails['budget'] = max($tripDetails['budget'] ?? 0, $this->totalPrice);
        Session::put('trip_details', $tripDetails);

        // Mark that user has trip data to save
        Session::put('trip_data_not_saved', true);

        // Always dispatch event to parent component to proceed to next step
        $this->dispatch('tripTemplateSelected', [
            'tripTemplateId' => $this->selectedTemplate->id,
            'proceedToNext' => true
        ]);
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