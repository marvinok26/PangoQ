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

    protected $listeners = [
        'viewTemplate' => 'handleViewTemplate',
        'restoreFromStorage' => 'restoreFromStorage'
    ];

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
        return view('livewire.trips.pre-planned-trip-selection', [
            'tripData' => $this->getTripDataForAlpine()
        ]);
    }

    /**
     * Get trip data for Alpine.js persistence
     */
    private function getTripDataForAlpine()
    {
        return [
            'selected_trip_template' => $this->selectedTemplate ? $this->selectedTemplate->id : null,
            'selected_destination' => $this->selectedDestination ? [
                'id' => $this->selectedDestination->id,
                'name' => $this->selectedDestination->name,
                'country' => $this->selectedDestination->country,
                'city' => $this->selectedDestination->city,
            ] : null,
            'selected_optional_activities' => $this->selectedOptionalActivities,
            'trip_pricing' => [
                'base_price' => $this->selectedTemplate ? $this->selectedTemplate->base_price : 0,
                'total_price' => $this->totalPrice
            ],
            'template_state' => [
                'show_details' => $this->showTemplateDetails,
                'template_highlights' => $this->templateHighlights
            ],
            'step' => 'pre_planned_trip_selection'
        ];
    }

    /**
     * Restore data from Alpine.js localStorage
     */
    public function restoreFromStorage($data)
    {
        // Restore destination selection
        if (isset($data['selected_destination']) && !$this->selectedDestination) {
            $destinationData = $data['selected_destination'];
            $this->selectedDestination = Destination::find($destinationData['id']);
            
            if ($this->selectedDestination) {
                $this->tripTemplates = TripTemplate::where('destination_id', $destinationData['id'])
                    ->with('destination')
                    ->get();
                    
                Session::put('selected_destination', $destinationData);
            }
        }

        // Restore template selection
        if (isset($data['selected_trip_template']) && !$this->selectedTemplate && $data['selected_trip_template']) {
            $templateId = $data['selected_trip_template'];
            $this->selectedTemplate = TripTemplate::with(['activities', 'destination'])->find($templateId);
            
            if ($this->selectedTemplate) {
                Session::put('selected_trip_template', $templateId);
                $this->loadTemplateDetails();
            }
        }

        // Restore optional activities
        if (isset($data['selected_optional_activities']) && !Session::has('selected_optional_activities')) {
            $this->selectedOptionalActivities = $data['selected_optional_activities'];
            Session::put('selected_optional_activities', $this->selectedOptionalActivities);
        }

        // Restore pricing
        if (isset($data['trip_pricing'])) {
            $pricing = $data['trip_pricing'];
            
            if (isset($pricing['total_price'])) {
                $this->totalPrice = $pricing['total_price'];
                Session::put('trip_total_price', $this->totalPrice);
            }
            
            if (isset($pricing['base_price'])) {
                Session::put('trip_base_price', $pricing['base_price']);
            }
        }

        // Restore template view state
        if (isset($data['template_state'])) {
            $state = $data['template_state'];
            
            if (isset($state['show_details'])) {
                $this->showTemplateDetails = $state['show_details'];
            }
            
            if (isset($state['template_highlights'])) {
                $this->templateHighlights = $state['template_highlights'];
            }
        }

        \Illuminate\Support\Facades\Log::info('Pre-planned trip data restored from storage');
    }

    /**
     * Sync current data to Alpine.js
     */
    private function syncDataToAlpine()
    {
        $this->dispatch('syncStepData', [
            'step' => 'pre_planned_trip_selection',
            'data' => $this->getTripDataForAlpine()
        ]);
    }

    /**
     * Load template details (extracted for reuse)
     */
    private function loadTemplateDetails()
    {
        if (!$this->selectedTemplate) return;

        // Parse the highlights field if it exists
        if ($this->selectedTemplate->highlights) {
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

        // Set initial total price if not already set
        if (!$this->totalPrice) {
            $this->totalPrice = $this->selectedTemplate->base_price;
        }
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

        // Sync with Alpine.js
        $this->syncDataToAlpine();
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

        // Load template details
        $this->loadTemplateDetails();

        // Sync with Alpine.js
        $this->syncDataToAlpine();
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

        // Sync with Alpine.js
        $this->syncDataToAlpine();
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

        // Sync with Alpine.js
        $this->syncDataToAlpine();

        // Always dispatch event to parent component to proceed to next step
        $this->dispatch('tripTemplateSelected', [
            'tripTemplateId' => $this->selectedTemplate->id,
            'proceedToNext' => true
        ]);
    }

    public function backToTemplates()
    {
        $this->showTemplateDetails = false;
        $this->syncDataToAlpine();
    }

    public function backToDestinations()
    {
        $this->selectedDestination = null;
        $this->tripTemplates = [];
        $this->syncDataToAlpine();
    }
}