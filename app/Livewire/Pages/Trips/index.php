<?php

namespace App\Livewire\Pages\Trips;

use App\Models\Trip;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\Attributes\Title;

class Index extends Component
{
    #[Title('My Trips')]
    public function render()
    {
        $trips = collect();
        
        if (Auth::check()) {
            $trips = Auth::user()->allTrips()->latest()->get();
        }
            
        return view('livewire.pages.trips.index', [
            'trips' => $trips
        ]);
    }
}