<?php

namespace App\Livewire\Trips;

use App\Models\Destination;
use App\Services\TripService;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class CreateTrip extends Component
{
    public string $title = '';
    public string $description = '';
    public string $destination = '';
    public ?string $start_date = null;
    public ?string $end_date = null;
    public ?float $budget = null;
    public string $privacy = 'friends';
    
    // Step tracking
    public int $currentStep = 1;
    public int $totalSteps = 3;
    
    // Destination search
    public string $destinationQuery = '';
    public array $destinationResults = [];
    public bool $showDestinationDropdown = false;
    
    // Invite friends
    public array $inviteEmails = [];
    public string $newEmail = '';
    
    protected $rules = [
        'title' => 'required|string|max:255',
        'description' => 'nullable|string',
        'destination' => 'required|string|max:255',
        'start_date' => 'required|date|after_or_equal:today',
        'end_date' => 'required|date|after_or_equal:start_date',
        'budget' => 'nullable|numeric|min:0',
        'privacy' => 'required|in:public,friends,private',
    ];
    
    public function mount()
    {
        // Set default dates (today and a week from today)
        $this->start_date = now()->format('Y-m-d');
        $this->end_date = now()->addDays(7)->format('Y-m-d');
    }
    
    public function render()
    {
        return view('livewire.trips.create-trip');
    }
    
    public function searchDestinations()
    {
        if (strlen($this->destinationQuery) >= 2) {
            $this->destinationResults = Destination::where('name->en', 'like', '%' . $this->destinationQuery . '%')
                ->orWhere('country', 'like', '%' . $this->destinationQuery . '%')
                ->orWhere('city', 'like', '%' . $this->destinationQuery . '%')
                ->take(5)
                ->get()
                ->toArray();
                
            $this->showDestinationDropdown = true;
        } else {
            $this->destinationResults = [];
            $this->showDestinationDropdown = false;
        }
    }
    
    public function selectDestination($destination)
    {
        if (is_array($destination)) {
            $this->destination = $destination['name']['en'] ?? $destination['name'];
        } else {
            $this->destination = $destination;
        }
        
        $this->destinationQuery = $this->destination;
        $this->showDestinationDropdown = false;
    }
    
    public function addEmail()
    {
        $this->validate([
            'newEmail' => 'required|email|not_in:' . implode(',', $this->inviteEmails),
        ]);
        
        $this->inviteEmails[] = $this->newEmail;
        $this->newEmail = '';
    }
    
    public function removeEmail($index)
    {
        unset($this->inviteEmails[$index]);
        $this->inviteEmails = array_values($this->inviteEmails);
    }
    
    public function nextStep()
    {
        if ($this->currentStep === 1) {
            $this->validate([
                'destination' => 'required|string|max:255',
                'start_date' => 'required|date|after_or_equal:today',
                'end_date' => 'required|date|after_or_equal:start_date',
            ]);
        } elseif ($this->currentStep === 2) {
            $this->validate([
                'title' => 'required|string|max:255',
                'privacy' => 'required|in:public,friends,private',
            ]);
        }
        
        $this->currentStep = min($this->currentStep + 1, $this->totalSteps);
    }
    
    public function previousStep()
    {
        $this->currentStep = max($this->currentStep - 1, 1);
    }
    
    public function createTrip()
    {
        $this->validate();
        
        $tripData = [
            'title' => $this->title,
            'description' => $this->description,
            'destination' => $this->destination,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'budget' => $this->budget,
        ];
        
        $tripService = app(TripService::class);
        $trip = $tripService->createTrip($tripData, Auth::id());
        
        // If there are emails to invite, send invitations
        if (count($this->inviteEmails) > 0) {
            $tripService->inviteMembers($trip, $this->inviteEmails);
        }
        
        session()->flash('success', 'Trip created successfully!');
        
        // Redirect to the trip page
        return redirect()->route('trips.show', $trip);
    }
    
    public function skipToSummary()
    {
        $this->currentStep = $this->totalSteps;
    }
}