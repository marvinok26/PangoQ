<?php

namespace App\Livewire\Pages\Trips;

use Livewire\Component;
use Livewire\Attributes\Title;

class Create extends Component
{
    #[Title('Plan a Trip')]
    public function render()
    {
        return view('livewire.pages.trips.create');
    }
}