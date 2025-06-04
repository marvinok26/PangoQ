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
            
            Session::put('trip_invites', $this->inviteEmails);

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
        
        Log::info('Skipped invites step');
        
        // Dispatch to parent component
        $this->dispatch('completeInvitesStep');
    }

    public function render()
    {
        return view('livewire.trips.invite-friends');
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