<?php

namespace App\Livewire\Trips;

use Livewire\Component;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class InviteFriends extends Component
{
    public $destination = '';
    public $tripTitle = '';
    public $tripType = '';
    public $friendName = '';
    public $friendEmail = '';
    public $personalMessage = '';
    public $inviteEmails = [];

    protected $rules = [
        'friendName' => 'required|string|max:255',
        'friendEmail' => 'required|email|max:255',
    ];

    protected $messages = [
        'friendName.required' => 'Please enter your friend\'s name.',
        'friendEmail.required' => 'Please enter a valid email address.',
        'friendEmail.email' => 'Please enter a valid email address.',
    ];

    protected $listeners = [
        'restoreFromStorage' => 'restoreFromStorage'
    ];

    public function mount()
    {
        Log::info('InviteFriends component mounting...');
        
        // Load trip data
        $this->loadTripData();
        $this->loadExistingInvites();
        
        Log::info('InviteFriends mounted successfully', [
            'destination' => $this->destination,
            'trip_type' => $this->tripType,
            'invite_count' => count($this->inviteEmails)
        ]);
    }

    public function render()
    {
        return view('livewire.trips.invite-friends', [
            'tripData' => $this->getTripDataForAlpine()
        ]);
    }

    /**
     * Get trip data for Alpine.js persistence
     */
    private function getTripDataForAlpine()
    {
        return [
            'trip_invites' => $this->inviteEmails,
            'invite_details' => [
                'destination' => $this->destination,
                'trip_title' => $this->tripTitle,
                'trip_type' => $this->tripType,
                'total_travelers' => $this->getTotalTravelersProperty()
            ],
            'step' => 'invite_friends'
        ];
    }

    /**
     * Restore data from Alpine.js localStorage
     */
    public function restoreFromStorage($data)
    {
        if (isset($data['trip_invites']) && !Session::has('trip_invites')) {
            $this->inviteEmails = $data['trip_invites'];
            Session::put('trip_invites', $this->inviteEmails);
            
            // Update travelers count in trip details
            $tripDetails = Session::get('trip_details', []);
            $tripDetails['travelers'] = 1 + count($this->inviteEmails);
            Session::put('trip_details', $tripDetails);
            
            Log::info('Friend invites restored from storage', [
                'invite_count' => count($this->inviteEmails)
            ]);
        }
    }

    /**
     * Sync current data to Alpine.js
     */
    private function syncDataToAlpine()
    {
        $this->dispatch('syncStepData', [
            'step' => 'invite_friends',
            'data' => $this->getTripDataForAlpine()
        ]);
    }

    private function loadTripData()
    {
        // Get destination
        $selectedDestination = Session::get('selected_destination');
        if ($selectedDestination) {
            if (is_array($selectedDestination)) {
                $this->destination = $selectedDestination['name'] ?? 'Your Destination';
            } else {
                $this->destination = $selectedDestination;
            }
        } else {
            $this->destination = 'Your Destination';
        }

        // Get trip details
        $tripDetails = Session::get('trip_details', []);
        $this->tripTitle = $tripDetails['title'] ?? 'Your Amazing Trip';

        // Get trip type
        $this->tripType = Session::get('selected_trip_type', 'self_planned');
    }

    private function loadExistingInvites()
    {
        $savedInvites = Session::get('trip_invites', []);
        if (is_array($savedInvites)) {
            $this->inviteEmails = $savedInvites;
        } else {
            $this->inviteEmails = [];
        }
    }

    public function addInvite()
    {
        $this->validate();

        // Check if email already exists
        $existingEmails = array_column($this->inviteEmails, 'email');
        if (in_array(strtolower($this->friendEmail), array_map('strtolower', $existingEmails))) {
            $this->addError('friendEmail', 'This email has already been invited.');
            return;
        }

        // Check if it's the current user's email
        if (Auth::check() && strtolower(Auth::user()->email) === strtolower($this->friendEmail)) {
            $this->addError('friendEmail', 'You cannot invite yourself.');
            return;
        }

        // Add to invite list
        $this->inviteEmails[] = [
            'name' => trim($this->friendName),
            'email' => strtolower(trim($this->friendEmail)),
            'message' => trim($this->personalMessage ?? ''),
            'status' => 'pending',
            'invited_at' => now()->toISOString()
        ];

        // Save to session
        Session::put('trip_invites', $this->inviteEmails);

        // Update travelers count in trip details
        $tripDetails = Session::get('trip_details', []);
        $tripDetails['travelers'] = 1 + count($this->inviteEmails);
        Session::put('trip_details', $tripDetails);

        // Sync with Alpine.js
        $this->syncDataToAlpine();

        // Reset form fields
        $this->reset(['friendName', 'friendEmail', 'personalMessage']);
        $this->resetValidation();

        Log::info('Friend invited', [
            'email' => $this->friendEmail,
            'total_invites' => count($this->inviteEmails)
        ]);

        session()->flash('invite_success', 'Invitation added successfully!');
    }

    public function removeInvite($index)
    {
        if (isset($this->inviteEmails[$index])) {
            $removedEmail = $this->inviteEmails[$index]['email'];
            
            unset($this->inviteEmails[$index]);
            $this->inviteEmails = array_values($this->inviteEmails);
            
            // Save to session
            Session::put('trip_invites', $this->inviteEmails);

            // Update travelers count in trip details
            $tripDetails = Session::get('trip_details', []);
            $tripDetails['travelers'] = 1 + count($this->inviteEmails);
            Session::put('trip_details', $tripDetails);

            // Sync with Alpine.js
            $this->syncDataToAlpine();

            Log::info('Invite removed', [
                'email' => $removedEmail,
                'remaining_invites' => count($this->inviteEmails)
            ]);
        }
    }

    public function skipInvites()
    {
        Log::info('Skip invites clicked');
        
        // Clear any existing invites
        $this->inviteEmails = [];
        Session::put('trip_invites', []);
        
        // Update travelers count to 1 (just organizer)
        $tripDetails = Session::get('trip_details', []);
        $tripDetails['travelers'] = 1;
        Session::put('trip_details', $tripDetails);
        
        // Sync with Alpine.js (clear invites)
        $this->dispatch('clearStepData', 'invite_friends');
        
        Log::info('Skipped invites step');
        
        // Dispatch to parent component
        $this->dispatch('completeInvitesStep');
    }

    // Computed properties
    public function getFormattedDatesProperty()
    {
        return 'Dates TBD';
    }

    public function getTotalTravelersProperty()
    {
        return 1 + count($this->inviteEmails);
    }

    public function getHasInvitesProperty()
    {
        return count($this->inviteEmails) > 0;
    }
}