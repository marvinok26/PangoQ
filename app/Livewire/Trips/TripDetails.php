<?php

namespace App\Livewire\Trips;

use Livewire\Component;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;

class TripDetails extends Component
{
    public $title;
    public $start_date;
    public $end_date;
    public $travelers = 4;
    public $budget;
    public $activityInterests = [];
    public $accommodationType;
    public $tripType;
    public $tripPace = 5;
    public $destination;

    protected $listeners = [
        'restoreFromStorage' => 'restoreFromStorage'
    ];

    public function mount()
    {
        // Set default dates (two weeks from now for one week)
        $this->start_date = now()->addWeeks(2)->format('Y-m-d');
        $this->end_date = now()->addWeeks(3)->format('Y-m-d');
        
        // Get destination from session
        $selectedDestination = session('selected_destination');
        
        if ($selectedDestination) {
            $this->destination = $selectedDestination['name'] ?? '';
            
            // Set default title based on destination
            if (empty($this->title) && isset($selectedDestination['name'])) {
                $this->title = "Trip to " . $selectedDestination['name'];
            }
        }
        
        // Load saved trip details if available
        $tripDetails = session('trip_details');
        if ($tripDetails) {
            $this->title = $tripDetails['title'] ?? $this->title;
            $this->start_date = $tripDetails['start_date'] ?? $this->start_date;
            $this->end_date = $tripDetails['end_date'] ?? $this->end_date;
            $this->travelers = $tripDetails['travelers'] ?? $this->travelers;
            $this->budget = $tripDetails['budget'] ?? $this->budget;
            $this->activityInterests = $tripDetails['activity_interests'] ?? $this->activityInterests;
            $this->accommodationType = $tripDetails['accommodation_type'] ?? $this->accommodationType;
            $this->tripType = $tripDetails['trip_type'] ?? $this->tripType;
            $this->tripPace = $tripDetails['trip_pace'] ?? $this->tripPace;
        }
    }

    public function render()
    {
        return view('livewire.trips.trip-details', [
            'tripData' => $this->getTripDataForAlpine()
        ]);
    }

    /**
     * Get trip data for Alpine.js persistence
     */
    private function getTripDataForAlpine()
    {
        return [
            'trip_details' => [
                'title' => $this->title,
                'start_date' => $this->start_date,
                'end_date' => $this->end_date,
                'travelers' => $this->travelers,
                'budget' => $this->budget,
                'activity_interests' => $this->activityInterests,
                'accommodation_type' => $this->accommodationType,
                'trip_type' => $this->tripType,
                'trip_pace' => $this->tripPace,
                'destination' => $this->destination
            ],
            'step' => 'trip_details'
        ];
    }

    /**
     * Restore data from Alpine.js localStorage
     */
    public function restoreFromStorage($data)
    {
        if (isset($data['trip_details']) && !Session::has('trip_details')) {
            $details = $data['trip_details'];
            
            $this->title = $details['title'] ?? $this->title;
            $this->start_date = $details['start_date'] ?? $this->start_date;
            $this->end_date = $details['end_date'] ?? $this->end_date;
            $this->travelers = $details['travelers'] ?? $this->travelers;
            $this->budget = $details['budget'] ?? $this->budget;
            $this->activityInterests = $details['activity_interests'] ?? $this->activityInterests;
            $this->accommodationType = $details['accommodation_type'] ?? $this->accommodationType;
            $this->tripType = $details['trip_type'] ?? $this->tripType;
            $this->tripPace = $details['trip_pace'] ?? $this->tripPace;
            $this->destination = $details['destination'] ?? $this->destination;
            
            // Also restore to session
            Session::put('trip_details', [
                'title' => $this->title,
                'start_date' => $this->start_date,
                'end_date' => $this->end_date,
                'travelers' => $this->travelers,
                'budget' => $this->budget,
                'activity_interests' => $this->activityInterests,
                'accommodation_type' => $this->accommodationType,
                'trip_type' => $this->tripType,
                'trip_pace' => $this->tripPace,
            ]);
            
            Log::info('Trip details restored from storage');
        }
    }

    public function updated($field)
    {
        // Validate fields on change
        if (in_array($field, ['title', 'start_date', 'end_date', 'travelers', 'budget'])) {
            $this->validateOnly($field, $this->getValidationRules());
        }

        // Auto-sync data to Alpine.js on field updates
        $this->syncDataToAlpine();
    }

    /**
     * Sync current data to Alpine.js
     */
    private function syncDataToAlpine()
    {
        $this->dispatch('syncStepData', [
            'step' => 'trip_details',
            'data' => $this->getTripDataForAlpine()
        ]);
    }

    public function getValidationRules()
    {
        return [
            'title' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'travelers' => 'required|integer|min:1',
            'budget' => 'nullable|numeric|min:0',
            'activityInterests' => 'nullable|array',
            'accommodationType' => 'nullable|string',
            'tripType' => 'nullable|string',
            'tripPace' => 'nullable|integer|min:1|max:10',
        ];
    }

    public function saveTripDetails()
    {
        // Validate data
        $validatedData = $this->validate($this->getValidationRules());

        // Prepare data for session storage
        $tripDetails = [
            'title' => $this->title,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'travelers' => $this->travelers,
            'budget' => $this->budget,
            'activity_interests' => $this->activityInterests,
            'accommodation_type' => $this->accommodationType,
            'trip_type' => $this->tripType,
            'trip_pace' => $this->tripPace,
        ];

        // Save to session
        session(['trip_details' => $tripDetails]);
        
        // Sync with Alpine.js
        $this->dispatch('syncStepData', [
            'step' => 'trip_details',
            'data' => $this->getTripDataForAlpine()
        ]);
        
        // Log for debugging
        Log::info("Trip Details saved, dispatching specific event to move to itinerary");
        
        // Use a specific named event instead of a generic one
        $this->dispatch('completeDetailsStep');
    }
}