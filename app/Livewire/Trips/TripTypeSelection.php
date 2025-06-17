<?php

namespace App\Livewire\Trips;

use Livewire\Component;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;

class TripTypeSelection extends Component
{
    public $selectedType = null;
    public $isAutoAdvancing = false;

    protected $listeners = [
        'restoreFromStorage' => 'restoreFromStorage'
    ];

    public function mount()
    {
        // Check if trip type was already selected
        $this->selectedType = Session::get('selected_trip_type');
        
        Log::info('TripTypeSelection mounted', [
            'existing_type' => $this->selectedType
        ]);
    }

    public function render()
    {
        return view('livewire.trips.trip-type-selection', [
            'tripData' => $this->getTripDataForAlpine()
        ]);
    }

    /**
     * Get trip data for Alpine.js persistence
     */
    private function getTripDataForAlpine()
    {
        return [
            'selected_trip_type' => $this->selectedType,
            'step' => 'trip_type_selection'
        ];
    }

    /**
     * Restore data from Alpine.js localStorage
     */
    public function restoreFromStorage($data)
    {
        if (isset($data['selected_trip_type']) && !$this->selectedType) {
            $this->selectedType = $data['selected_trip_type'];
            Session::put('selected_trip_type', $this->selectedType);
            
            Log::info('Trip type restored from storage', [
                'type' => $this->selectedType
            ]);
        }
    }
    
    public function selectTripType($type)
    {
        if (!in_array($type, ['pre_planned', 'self_planned'])) {
            Log::warning('Invalid trip type selected', ['type' => $type]);
            return;
        }

        $this->selectedType = $type;
        $this->isAutoAdvancing = true;
        
        // Store the selected trip type in session
        Session::put('selected_trip_type', $type);
        
        // Sync with Alpine.js
        $this->dispatch('syncStepData', [
            'step' => 'trip_type_selection',
            'data' => $this->getTripDataForAlpine()
        ]);
        
        Log::info('Trip type selected', [
            'type' => $type,
            'session_stored' => Session::get('selected_trip_type')
        ]);
        
        // Small delay for better UX, then dispatch event
        $this->dispatch('tripTypeSelected', tripType: $type);
    }

    /**
     * Clear selection (useful for testing or if user wants to change)
     */
    public function clearSelection()
    {
        $this->selectedType = null;
        $this->isAutoAdvancing = false;
        Session::forget('selected_trip_type');
        
        // Clear from Alpine.js storage
        $this->dispatch('clearStepData', 'trip_type_selection');
        
        Log::info('Trip type selection cleared');
    }
}