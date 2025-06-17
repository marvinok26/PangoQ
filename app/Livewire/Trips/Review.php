<?php

namespace App\Livewire\Trips;

use App\Models\TripTemplate;
use Illuminate\Support\Facades\Log;
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
    public $templateHighlights;
    public $selectedOptionalActivities = [];
    public $optionalActivities = [];
    public $basePrice;
    public $totalCost;

    protected $listeners = [
        'restoreFromStorage' => 'restoreFromStorage'
    ];

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
                // Ensure we get a single model instance, not a collection
                $this->tripTemplate = TripTemplate::with(['activities', 'destination'])
                    ->where('id', $templateId)
                    ->first();

                if ($this->tripTemplate) {
                    // Parse the highlights JSON field if it exists - using direct property access
                    $highlightsData = $this->tripTemplate->highlights;
                    if ($highlightsData) {
                        // Check if highlights is already an array
                        $this->templateHighlights = is_array($highlightsData)
                            ? $highlightsData
                            : json_decode($highlightsData, true) ?? [];
                    } else {
                        $this->templateHighlights = [];
                    }

                    // Get base price - using direct property access
                    $this->basePrice = $this->tripTemplate->base_price ?? 0;

                    // Get optional activities
                    $this->optionalActivities = $this->tripTemplate->activities()
                        ->where('is_optional', true)
                        ->get();

                    // Get selected optional activities from session
                    $this->selectedOptionalActivities = session('selected_optional_activities', []);

                    // Try to get total price from session first
                    $this->totalCost = session('trip_total_price', $this->basePrice);

                    // If no total price in session, calculate it
                    if ($this->totalCost == $this->basePrice && !empty($this->selectedOptionalActivities)) {
                        foreach ($this->selectedOptionalActivities as $id => $activity) {
                            if (isset($activity['cost'])) {
                                $this->totalCost += $activity['cost'];
                            }
                        }
                    }

                    // Make sure trip details has the correct total cost
                    if ((!isset($this->tripDetails['total_cost']) || $this->tripDetails['total_cost'] != $this->totalCost)) {
                        $this->tripDetails['total_cost'] = $this->totalCost;
                        session(['trip_details' => $this->tripDetails]);
                    }

                    // Group activities by day (only non-optional)
                    $activities = $this->tripTemplate->activities()
                        ->where('is_optional', false)
                        ->get();

                    $groupedActivities = [];

                    foreach ($activities as $activity) {
                        $groupedActivities[$activity->day_number][] = $activity;
                    }

                    // Sort activities by start_time for each day
                    foreach ($groupedActivities as $day => $dayActivities) {
                        usort($dayActivities, function ($a, $b) {
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
        return view('livewire.trips.review', [
            'tripData' => $this->getTripDataForAlpine()
        ]);
    }

    /**
     * Get trip data for Alpine.js persistence
     */
    private function getTripDataForAlpine()
    {
        return [
            'review_summary' => [
                'trip_type' => $this->tripType,
                'destination' => $this->destination,
                'trip_details' => $this->tripDetails,
                'trip_activities' => $this->tripActivities,
                'invites' => $this->invites,
                'selected_optional_activities' => $this->selectedOptionalActivities,
                'base_price' => $this->basePrice,
                'total_cost' => $this->totalCost,
                'template_id' => session('selected_trip_template'),
                'template_highlights' => $this->templateHighlights
            ],
            'step' => 'review'
        ];
    }

    /**
     * Restore data from Alpine.js localStorage
     */
    public function restoreFromStorage($data)
    {
        if (isset($data['review_summary'])) {
            $summary = $data['review_summary'];
            
            // Restore basic trip data if not in session
            if (!$this->tripType && isset($summary['trip_type'])) {
                $this->tripType = $summary['trip_type'];
                Session::put('selected_trip_type', $this->tripType);
            }
            
            if (!$this->destination && isset($summary['destination'])) {
                $this->destination = $summary['destination'];
                Session::put('selected_destination', $this->destination);
            }
            
            if (empty($this->tripDetails) && isset($summary['trip_details'])) {
                $this->tripDetails = $summary['trip_details'];
                Session::put('trip_details', $this->tripDetails);
            }
            
            if (empty($this->tripActivities) && isset($summary['trip_activities'])) {
                $this->tripActivities = $summary['trip_activities'];
                Session::put('trip_activities', $this->tripActivities);
            }
            
            if (empty($this->invites) && isset($summary['invites'])) {
                $this->invites = $summary['invites'];
                Session::put('trip_invites', $this->invites);
            }
            
            if (empty($this->selectedOptionalActivities) && isset($summary['selected_optional_activities'])) {
                $this->selectedOptionalActivities = $summary['selected_optional_activities'];
                Session::put('selected_optional_activities', $this->selectedOptionalActivities);
            }
            
            if (isset($summary['template_id']) && !Session::has('selected_trip_template')) {
                Session::put('selected_trip_template', $summary['template_id']);
            }
            
            if (isset($summary['total_cost'])) {
                $this->totalCost = $summary['total_cost'];
                Session::put('trip_total_price', $this->totalCost);
            }
            
            if (isset($summary['base_price'])) {
                $this->basePrice = $summary['base_price'];
                Session::put('trip_base_price', $this->basePrice);
            }
            
            Log::info('Review data restored from storage');
        }
    }

    /**
     * Sync current data to Alpine.js
     */
    private function syncDataToAlpine()
    {
        $this->dispatch('syncStepData', [
            'step' => 'review',
            'data' => $this->getTripDataForAlpine()
        ]);
    }

    public function editTripType()
    {
        $this->syncDataToAlpine();
        $this->dispatch('goToStep', step: 0);
    }

    public function editDestination()
    {
        $this->syncDataToAlpine();
        $this->dispatch('goToStep', step: 1);
    }

    public function editDetails()
    {
        $this->syncDataToAlpine();
        $this->dispatch('goToStep', step: 2);
    }

    public function editItinerary()
    {
        $this->syncDataToAlpine();
        $this->dispatch('goToStep', step: 3);
    }

    public function editInvites()
    {
        $this->syncDataToAlpine();
        $this->dispatch('goToStep', step: 4);
    }
}