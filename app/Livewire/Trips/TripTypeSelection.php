<?php

namespace App\Livewire\Trips;

use Livewire\Component;
use Illuminate\Support\Facades\Session;

class TripTypeSelection extends Component
{
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