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
        $trips = Auth::check() 
            ? Auth::user()->trips()->latest()->get() 
            : collect();
            
        return view('livewire.pages.trips.index', [
            'trips' => $trips
        ]);
    }
}