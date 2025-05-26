<?php

namespace App\Livewire\Trips;

use Livewire\Component;
use Illuminate\Support\Facades\Session;

class TripTypeSelection extends Component
{
    public $preSelectedType = null;

    public function mount()
    {
        // Check if trip type was already selected from the welcome page
        $this->preSelectedType = Session::get('selected_trip_type');
        
        // If there's a pre-selected type, automatically proceed to next step
        if ($this->preSelectedType) {
            // Dispatch immediately since type is already selected
            $this->dispatch('tripTypeSelected', tripType: $this->preSelectedType);
        }
    }

    public function render()
    {
        return view('livewire.trips.trip-type-selection');
    }
    
    public function selectTripType($type)
    {
        if ($type === 'pre_planned' || $type === 'self_planned') {
            // Store the selected trip type in session
            Session::put('selected_trip_type', $type);
            
            // Dispatch event to parent component
            $this->dispatch('tripTypeSelected', tripType: $type);
        }
    }
}