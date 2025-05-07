<?php

namespace App\Http\Livewire\Trips;

use Livewire\Component;

class DestinationSelection extends Component
{
    public string $destinationQuery = '';
    public array $destinationResults = [];
    public string $selectedDestination = '';
    public bool $showDestinationDropdown = false;

    public function searchDestinations()
    {
        // Fake search logic
        $mockDestinations = [
            ['name' => ['en' => 'Bali'], 'city' => 'Denpasar', 'country' => 'Indonesia'],
            ['name' => ['en' => 'Santorini'], 'city' => 'Thira', 'country' => 'Greece'],
            ['name' => ['en' => 'Tokyo'], 'city' => 'Tokyo', 'country' => 'Japan'],
        ];

        if (strlen($this->destinationQuery) >= 2) {
            $this->destinationResults = array_filter($mockDestinations, function ($dest) {
                return stripos($dest['name']['en'], $this->destinationQuery) !== false;
            });

            $this->showDestinationDropdown = true;
        } else {
            $this->destinationResults = [];
            $this->showDestinationDropdown = false;
        }
    }

    public function selectDestination($name)
    {
        $this->selectedDestination = $name;
        $this->destinationQuery = $name;
        $this->showDestinationDropdown = false;
    }

    public function nextStep()
    {
        if (empty($this->selectedDestination)) {
            $this->addError('selectedDestination', 'Please select a destination first.');
            return;
        }

        // Emit an event or redirect
        return redirect()->route('trips.details'); // or emit('goToStep', 2) if using step wizard
    }

    public function cancel()
    {
        return redirect()->route('home');
    }

    public function render()
    {
        return view('livewire.trips.destination-selection');
    }
}
