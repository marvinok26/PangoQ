<?php

namespace App\Livewire\Trips;

use Livewire\Component;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class CreateTrip extends Component
{
    public $currentStep = 0;
    public $totalSteps = 5;
    public $tripType = null;
    public $tripTemplateId = null;
    public $destination = null;
    public $stepNames = [];
    public $canProceed = false;

    protected $listeners = [
        'tripTypeSelected' => 'selectTripType',
        'tripTemplateSelected' => 'selectTripTemplate',
        'destinationSelected' => 'selectDestination',
        'completeDetailsStep' => 'moveToItinerary',
        'completeItineraryStep' => 'moveToInvites',
        'completeInvitesStep' => 'moveToReview',
        'goToPreviousStep' => 'previousStep',
        'goToStep' => 'goToStep'
    ];

    public function mount()
    {
        $this->initializeSteps();
        $this->loadSessionData();
        $this->validateCurrentStep();
        
        Log::info("CreateTrip mounted", [
            'step' => $this->currentStep,
            'trip_type' => $this->tripType,
            'has_destination' => !is_null($this->destination),
            'can_proceed' => $this->canProceed
        ]);
    }

    public function render()
    {
        return view('livewire.trips.create-trip');
    }

    /**
     * Initialize step configuration based on trip type
     */
    private function initializeSteps()
    {
        // Check if we have a trip type from session to determine step configuration
        $sessionTripType = Session::get('selected_trip_type');
        
        if ($sessionTripType === 'pre_planned') {
            $this->stepNames = [
                0 => 'Trip Type',
                1 => 'Choose Template', 
                2 => 'Invite Friends',
                3 => 'Review & Create'
            ];
            $this->totalSteps = 4;
        } else {
            $this->stepNames = [
                0 => 'Trip Type',
                1 => 'Destination',
                2 => 'Trip Details', 
                3 => 'Plan Itinerary',
                4 => 'Invite Friends',
                5 => 'Review & Create'
            ];
            $this->totalSteps = 6;
        }
    }

    /**
     * Load existing session data and determine current step
     */
    private function loadSessionData()
    {
        $this->tripType = Session::get('selected_trip_type');
        $this->tripTemplateId = Session::get('selected_trip_template');
        $this->destination = Session::get('selected_destination');

        // Determine current step based on completed data
        if (!$this->tripType) {
            $this->currentStep = 0; // Trip Type Selection
        } elseif ($this->tripType === 'pre_planned') {
            if (!$this->tripTemplateId) {
                $this->currentStep = 1; // Template Selection
            } elseif (!Session::has('trip_invites')) {
                $this->currentStep = 2; // Invite Friends
            } else {
                $this->currentStep = 3; // Review
            }
        } else { // self_planned
            if (!$this->destination) {
                $this->currentStep = 1; // Destination Selection
            } elseif (!Session::has('trip_details')) {
                $this->currentStep = 2; // Trip Details
            } elseif (!Session::has('trip_activities')) {
                $this->currentStep = 3; // Itinerary Planning
            } elseif (!Session::has('trip_invites')) {
                $this->currentStep = 4; // Invite Friends
            } else {
                $this->currentStep = 5; // Review
            }
        }

        // Re-initialize steps if trip type changed
        if ($this->tripType) {
            $this->initializeSteps();
        }
    }

    /**
     * Validate if current step requirements are met
     */
    private function validateCurrentStep()
    {
        $this->canProceed = false;

        switch ($this->currentStep) {
            case 0: // Trip Type Selection
                $this->canProceed = !is_null($this->tripType);
                break;
                
            case 1: // Destination or Template Selection
                if ($this->tripType === 'pre_planned') {
                    $this->canProceed = !is_null($this->tripTemplateId);
                } else {
                    $this->canProceed = !is_null($this->destination);
                }
                break;
                
            case 2: // Trip Details (self-planned) or Invite Friends (pre-planned)
                if ($this->tripType === 'pre_planned') {
                    $this->canProceed = true; // Can always proceed to invites
                } else {
                    $this->canProceed = Session::has('trip_details');
                }
                break;
                
            case 3: // Itinerary (self-planned) or Review (pre-planned)
                if ($this->tripType === 'pre_planned') {
                    $this->canProceed = true; // Can always proceed to review
                } else {
                    $this->canProceed = true; // Can proceed with or without activities
                }
                break;
                
            case 4: // Invite Friends (self-planned)
                $this->canProceed = true; // Can proceed with or without invites
                break;
                
            case 5: // Review (self-planned)
                $this->canProceed = true;
                break;
        }
    }

    public function selectTripType($tripType)
    {
        $this->tripType = $tripType;
        Session::put('selected_trip_type', $tripType);

        // Reset steps configuration
        $this->initializeSteps();
        
        // Move to next step
        $this->currentStep = 1;
        $this->validateCurrentStep();

        Log::info("Trip type selected", [
            'type' => $tripType,
            'new_step' => $this->currentStep,
            'total_steps' => $this->totalSteps
        ]);
    }

    public function selectTripTemplate($tripTemplateId)
    {
        $this->tripTemplateId = $tripTemplateId;
        Session::put('selected_trip_template', $tripTemplateId);

        // For pre-planned trips, move to invite friends
        $this->currentStep = 2;
        $this->validateCurrentStep();

        Log::info("Trip template selected", [
            'template_id' => $tripTemplateId,
            'new_step' => $this->currentStep
        ]);
    }

    public function selectDestination($destination)
    {
        $this->destination = $destination;
        Session::put('selected_destination', $destination);

        // Move to trip details
        $this->currentStep = 2;
        $this->validateCurrentStep();

        Log::info("Destination selected", [
            'destination' => $destination['name'] ?? 'Unknown',
            'new_step' => $this->currentStep
        ]);
    }

    public function moveToItinerary()
    {
        if ($this->tripType === 'self_planned' && $this->currentStep === 2) {
            $this->currentStep = 3;
            $this->validateCurrentStep();
            Log::info("Moved to itinerary planning step");
        }
    }

    public function moveToInvites()
    {
        if ($this->tripType === 'pre_planned' && $this->currentStep === 1) {
            $this->currentStep = 2;
        } elseif ($this->tripType === 'self_planned' && $this->currentStep === 3) {
            $this->currentStep = 4;
        }
        $this->validateCurrentStep();
        Log::info("Moved to invites step");
    }

    public function moveToReview()
    {
        if ($this->tripType === 'pre_planned' && $this->currentStep === 2) {
            $this->currentStep = 3;
        } elseif ($this->tripType === 'self_planned' && $this->currentStep === 4) {
            $this->currentStep = 5;
        }
        $this->validateCurrentStep();
        Log::info("Moved to review step");
    }

    public function previousStep()
    {
        if ($this->currentStep > 0) {
            $oldStep = $this->currentStep;
            $this->currentStep--;
            $this->validateCurrentStep();
            
            Log::info("Moved back", [
                'from' => $oldStep,
                'to' => $this->currentStep
            ]);
        }
    }

    public function nextStep()
    {
        if ($this->canProceed && $this->currentStep < ($this->totalSteps - 1)) {
            $oldStep = $this->currentStep;
            $this->currentStep++;
            $this->validateCurrentStep();
            
            Log::info("Moved forward", [
                'from' => $oldStep,
                'to' => $this->currentStep
            ]);
        }
    }

    public function goToStep($step)
    {
        // Only allow going to previous steps or current step + 1 if requirements are met
        if ($step >= 0 && $step < $this->totalSteps) {
            if ($step <= $this->currentStep || ($step === $this->currentStep + 1 && $this->canProceed)) {
                $this->currentStep = $step;
                $this->validateCurrentStep();
                
                Log::info("Navigated to step", ['step' => $step]);
            }
        }
    }

    public function skipToSummary()
    {
        // Only allow skipping if we have minimum required data
        $hasMinimumData = false;
        
        if ($this->tripType === 'pre_planned' && $this->tripTemplateId) {
            $hasMinimumData = true;
            $this->currentStep = 3; // Review for pre-planned
        } elseif ($this->tripType === 'self_planned' && $this->destination && Session::has('trip_details')) {
            $hasMinimumData = true;
            $this->currentStep = 5; // Review for self-planned
        }
        
        if ($hasMinimumData) {
            $this->validateCurrentStep();
            Log::info("Skipped to summary");
        }
    }

    /**
     * Get progress percentage for the progress bar
     */
    public function getProgressPercentageProperty()
    {
        if ($this->totalSteps <= 1) return 100;
        
        return min(100, round(($this->currentStep / ($this->totalSteps - 1)) * 100));
    }

    /**
     * Get current step name for display
     */
    public function getCurrentStepNameProperty()
    {
        return $this->stepNames[$this->currentStep] ?? 'Step ' . ($this->currentStep + 1);
    }

    /**
     * Check if we can show the skip to summary option
     */
    public function getCanSkipToSummaryProperty()
    {
        if ($this->currentStep >= ($this->totalSteps - 1)) return false;
        
        if ($this->tripType === 'pre_planned') {
            return $this->tripTemplateId && $this->currentStep < 3;
        } else {
            return $this->destination && Session::has('trip_details') && $this->currentStep < 5;
        }
    }

    /**
     * Handle the create trip button click
     */
    public function createTrip()
    {
        // Store trip data in session for later use
        Session::put('trip_creation_pending', true);
        
        // Ensure we have all required data
        $this->ensureCompleteSessionData();

        // Log session data for debugging
        Log::info('Trip creation initiated', [
            'trip_type' => Session::get('selected_trip_type'),
            'destination' => Session::get('selected_destination'),
            'template_id' => Session::get('selected_trip_template'),
            'has_details' => Session::has('trip_details'),
            'has_activities' => Session::has('trip_activities'),
            'has_invites' => Session::has('trip_invites'),
            'user_authenticated' => Auth::check()
        ]);

        // Set flash message for the login page
        Session::flash('login_message', 'Please login or create an account to save your trip plans.');
        
        // Set intended URL after login
        Session::put('url.intended', route('trips.create'));

        // Check if user is authenticated
        if (Auth::check()) {
            // User is logged in, redirect to trips index or create trip logic
            return $this->redirect(route('trips.index'));
        } else {
            // User is not logged in, redirect to login page
            return $this->redirect(route('login'));
        }
    }

    /**
     * Ensure all required session data is complete before saving
     */
    private function ensureCompleteSessionData()
    {
        $tripDetails = Session::get('trip_details', []);
        $totalPrice = Session::get('trip_total_price', 0);

        // Set default dates if missing
        if (!isset($tripDetails['start_date'])) {
            $tripDetails['start_date'] = now()->addWeeks(2)->format('Y-m-d');
        }
        if (!isset($tripDetails['end_date'])) {
            $tripDetails['end_date'] = now()->addWeeks(3)->format('Y-m-d');
        }

        // Set default title if missing
        if (!isset($tripDetails['title'])) {
            $destination = Session::get('selected_destination');
            $tripDetails['title'] = 'Trip to ' . ($destination['name'] ?? 'Amazing Destination');
        }

        // Ensure budget and total cost are set
        if (!isset($tripDetails['total_cost'])) {
            $tripDetails['total_cost'] = $totalPrice > 0 ? $totalPrice : ($tripDetails['budget'] ?? 0);
        }
        if (!isset($tripDetails['budget']) || $tripDetails['budget'] < $tripDetails['total_cost']) {
            $tripDetails['budget'] = $tripDetails['total_cost'];
        }

        // Set default travelers if missing
        if (!isset($tripDetails['travelers'])) {
            $invites = Session::get('trip_invites', []);
            $tripDetails['travelers'] = count($invites) + 1; // +1 for organizer
        }

        Session::put('trip_details', $tripDetails);
    }
}