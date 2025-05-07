<?php

namespace App\Livewire\Trips;

use Livewire\Component;
use App\Models\Destination;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;

class CreateTrip extends Component
{
    public $currentStep = 1;
    public $totalSteps = 5;
    public $showNavButtons = false;
    
    // Use specific events for each step transition
    protected $listeners = [
        'destinationSelected' => 'selectDestination',
        'completeDetailsStep' => 'moveToItinerary',
        'completeItineraryStep' => 'moveToInvites',
        'completeInvitesStep' => 'moveToReview',
        'goToPreviousStep' => 'previousStep'
    ];
    
    public function mount()
    {
        Log::info("CreateTrip component mounted with step: {$this->currentStep}");
    }
    
    public function render()
    {
        // Debug info
        session(['debug_info' => "Current step: {$this->currentStep}"]);
        
        return view('livewire.trips.create-trip');
    }
    
    // Specific transition methods for each step
    public function moveToItinerary()
    {
        Log::info("Moving to Itinerary (step 3) from step: {$this->currentStep}");
        $this->currentStep = 3;
    }
    
    public function moveToInvites()
    {
        Log::info("Moving to Invites (step 4) from step: {$this->currentStep}");
        $this->currentStep = 4;
    }
    
    public function moveToReview()
    {
        Log::info("Moving to Review (step 5) from step: {$this->currentStep}");
        $this->currentStep = 5;
    }
    
    public function previousStep()
    {
        $oldStep = $this->currentStep;
        if ($this->currentStep > 1) {
            $this->currentStep--;
        }
        Log::info("Moved back from step {$oldStep} to {$this->currentStep}");
    }
    
    public function goToStep($step)
    {
        $oldStep = $this->currentStep;
        // Only allow valid steps
        if ($step >= 1 && $step <= $this->totalSteps) {
            // Only allow backward navigation or one step forward
            if ($step < $this->currentStep || $step == $this->currentStep + 1) {
                $this->currentStep = $step;
                Log::info("Manually navigated from step {$oldStep} to {$this->currentStep}");
            }
        }
    }
    
    public function skipToSummary()
    {
        if (session('selected_destination') && session('trip_details')) {
            $oldStep = $this->currentStep;
            $this->currentStep = $this->totalSteps;
            Log::info("Skipped from step {$oldStep} to summary (step 5)");
        }
    }
    
    public function selectDestination($destination)
    {
        // Store destination
        Session::put('selected_destination', $destination);
        
        // Go to Trip Details
        $oldStep = $this->currentStep;
        $this->currentStep = 2;
        Log::info("Selected destination, moved from step {$oldStep} to 2");
    }
    
    public function createTrip()
    {
        // Get trip data
        $selectedDestination = session('selected_destination');
        $tripDetails = session('trip_details');
        
        if (!$selectedDestination || !$tripDetails) {
            $oldStep = $this->currentStep;
            $this->currentStep = !$selectedDestination ? 1 : 2;
            Log::info("Missing data, moved from step {$oldStep} to {$this->currentStep}");
            return;
        }
        
        // Success!
        session()->flash('message', 'Trip created successfully!');
        return redirect()->route('dashboard');
    }
}