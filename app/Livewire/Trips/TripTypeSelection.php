<?php

namespace App\Livewire\Trips;

use Livewire\Component;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;

class TripTypeSelection extends Component
{
    public $selectedType = null;
    public $isAutoAdvancing = false;

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
        return view('livewire.trips.trip-type-selection');
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
        
        Log::info('Trip type selection cleared');
    }
}