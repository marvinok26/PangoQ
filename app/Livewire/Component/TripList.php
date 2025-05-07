<?php

namespace App\Livewire\Trips;

use Livewire\Component;
use App\Models\Trip;
use Illuminate\Support\Facades\Auth;

class TripList extends Component
{
    public $trips;

    public function mount()
    {
        $this->loadTrips();
    }

    public function render()
    {
        return view('livewire.trips.index');
    }

    private function loadTrips()
    {
        $user = Auth::user();
        
        // Get trips where the user is the creator or a member
        $this->trips = Trip::where('creator_id', $user->id)
            ->orWhereHas('members', function($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->orderBy('created_at', 'desc')
            ->get();
    }
}