<?php

namespace App\Livewire\Trips;

use Livewire\Component;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;

class CreateTrip extends Component
{
    public $currentStep = 0; // Start at step 0 for trip type selection
    public $totalSteps = 5;
    public $showNavButtons = false;
    public $tripType = null;
    public $tripTemplateId = null;

    // Use specific events for each step transition
    protected $listeners = [
        'tripTypeSelected' => 'selectTripType',
        'tripTemplateSelected' => 'selectTripTemplate',
        'destinationSelected' => 'selectDestination',
        'completeDetailsStep' => 'moveToItinerary',
        'completeItineraryStep' => 'moveToInvites',
        'completeInvitesStep' => 'moveToReview',
        'goToPreviousStep' => 'previousStep'
    ];

    public function mount()
    {
        // Reset step to 0 for the trip type selection
        $this->currentStep = 0;

        // Check if we already have a trip type selected in session
        $this->tripType = session('selected_trip_type');
        $this->tripTemplateId = session('selected_trip_template');

        // If trip type is already selected, start at step 1 (destination or template selection)
        if ($this->tripType) {
            $this->currentStep = 1;
        }

        Log::info("CreateTrip component mounted with step: {$this->currentStep}, trip type: {$this->tripType}");
    }

    public function render()
    {
        // Debug info
        session(['debug_info' => "Current step: {$this->currentStep}, Trip type: {$this->tripType}"]);

        return view('livewire.trips.create-trip');
    }

    public function selectTripType($tripType)
    {
        $this->tripType = $tripType;
        Session::put('selected_trip_type', $tripType);

        $this->currentStep = 1; // Move to either destination selection or template selection

        Log::info("Trip type selected: {$tripType}, moved to step 1");
    }

    public function selectTripTemplate($tripTemplateId)
    {
        $this->tripTemplateId = $tripTemplateId;
        Session::put('selected_trip_template', $tripTemplateId);

        // Pre-planned trips skip steps 1-3 since the template already has them
        // Move directly to invites (step 4)
        $this->currentStep = 4;

        Log::info("Trip template selected: {$tripTemplateId}, moved to step 4 (invites)");
    }

    public function selectDestination($destination)
    {
        // Store destination (for self-planned trips)
        Session::put('selected_destination', $destination);

        // Go to Trip Details
        $oldStep = $this->currentStep;
        $this->currentStep = 2;
        Log::info("Selected destination, moved from step {$oldStep} to 2");
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

        // Special handling for pre-planned trips
        if ($this->tripType === 'pre_planned' && $this->currentStep === 4) {
            // Go back to template selection
            $this->currentStep = 1;
        } else if ($this->currentStep > 0) {
            // Normal step backwards
            $this->currentStep--;
        }

        Log::info("Moved back from step {$oldStep} to {$this->currentStep}");
    }

    public function goToStep($step)
    {
        $oldStep = $this->currentStep;

        // Only allow valid steps and consider trip type
        if ($step >= 0 && $step <= $this->totalSteps) {
            // For pre-planned trips, only allow steps 0, 1, 4 and 5
            if ($this->tripType === 'pre_planned') {
                if ($step == 0 || $step == 1 || $step == 4 || $step == 5) {
                    $this->currentStep = $step;
                    Log::info("Manually navigated from step {$oldStep} to {$this->currentStep}");
                }
            }
            // For self-planned trips or initial selection, allow normal flow
            else {
                // Only allow backward navigation or one step forward
                if ($step < $this->currentStep || $step == $this->currentStep + 1) {
                    $this->currentStep = $step;
                    Log::info("Manually navigated from step {$oldStep} to {$this->currentStep}");
                }
            }
        }
    }

    public function skipToSummary()
    {
        $oldStep = $this->currentStep;

        if ($this->tripType === 'pre_planned' && $this->tripTemplateId) {
            $this->currentStep = $this->totalSteps; // Go to review
            Log::info("Skipped from step {$oldStep} to summary (step 5) for pre-planned trip");
        } else if ($this->tripType === 'self_planned' && session('selected_destination') && session('trip_details')) {
            $this->currentStep = $this->totalSteps; // Go to review
            Log::info("Skipped from step {$oldStep} to summary (step 5) for self-planned trip");
        }
    }

    /**
     * Handle the create trip button click
     * Saves trip data to session and redirects to login
     */
    public function createTrip()
{
    // Mark session data as "new" so it will be saved after login
    session(['trip_data_not_saved' => true]);
    
    // Add a flash message for the login/register page
    session()->flash('message', 'Please login or create an account to save your trip plans.');
    
    // Check if user is authenticated
    if (auth()->check()) {
        // User is already logged in, save trip directly
        return redirect()->route('trips.index');
    } else {
        // User is not logged in, redirect to login
        return redirect()->route('login');
    }
}


}

Log::info('Trip data before redirect to login', [
    'trip_data_not_saved' => session('trip_data_not_saved'),
    'selected_trip_type' => session('selected_trip_type'),
    'selected_destination' => session('selected_destination'),
    'trip_details' => session('trip_details'),
    'session_id' => session()->getId()
]);
