<?php

namespace App\Livewire\Trips;

use Livewire\Component;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;

class InviteFriends extends Component
{
    public $destination;
    public $tripTitle;
    public $startDate;
    public $endDate;
    public $travelers = 1; // Default to 1 (just the organizer)
    
    public $friendName = '';
    public $friendContact = '';
    public $personalMessage = '';
    public $inviteEmails = [];
    public $showBudget = true;  // For toggling budget visibility
    public $showBulkInvite = false;
    public $bulkEmails = '';
    public $bulkMessage = '';
    
    public function mount()
    {
        // Get trip details from session
        $selectedDestination = session('selected_destination');
        $tripDetails = session('trip_details');
        
        if ($selectedDestination) {
            $this->destination = $selectedDestination['name'] ?? '';
        }
        
        if ($tripDetails) {
            $this->tripTitle = $tripDetails['title'] ?? '';
            $this->startDate = $tripDetails['start_date'] ?? '';
            $this->endDate = $tripDetails['end_date'] ?? '';
            // Only set $travelers from session if it's a valid number greater than 0
            if (isset($tripDetails['travelers']) && $tripDetails['travelers'] > 0) {
                $this->travelers = $tripDetails['travelers'];
            }
        }
        
        // Load saved invites if available
        $savedInvites = session('trip_invites');
        if ($savedInvites) {
            $this->inviteEmails = $savedInvites;
        }
    }

    public function render()
    {
        return view('livewire.trips.invite-friends');
    }
    
    public function addInvite()
    {
        $this->validate([
            'friendName' => 'required|string|max:255',
            'friendContact' => 'required|email',
        ]);
        
        // Add to invite list
        $this->inviteEmails[] = [
            'name' => $this->friendName,
            'email' => $this->friendContact,
            'message' => $this->personalMessage,
            'status' => 'pending'
        ];
        
        // Reset form fields
        $this->reset(['friendName', 'friendContact', 'personalMessage']);
        
        // Save to session
        session(['trip_invites' => $this->inviteEmails]);
    }
    
    public function removeInvite($index)
    {
        if (isset($this->inviteEmails[$index])) {
            // Remove the invite at the specified index
            unset($this->inviteEmails[$index]);
            
            // Re-index array
            $this->inviteEmails = array_values($this->inviteEmails);
            
            // Save to session
            session(['trip_invites' => $this->inviteEmails]);
        }
    }
    
    public function processBulkInvite()
    {
        $this->validate([
            'bulkEmails' => 'required|string',
        ]);
        
        // Split by comma, semicolon, or new line
        $emails = preg_split('/[\s,;]+/', $this->bulkEmails);
        $validEmails = [];
        
        foreach ($emails as $email) {
            $email = trim($email);
            
            if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $validEmails[] = [
                    'name' => '',  // No name for bulk invites
                    'email' => $email,
                    'message' => $this->bulkMessage,
                    'status' => 'pending'
                ];
            }
        }
        
        // Add to invite list
        $this->inviteEmails = array_merge($this->inviteEmails, $validEmails);
        
        // Reset form fields
        $this->reset(['bulkEmails', 'bulkMessage', 'showBulkInvite']);
        
        // Save to session
        session(['trip_invites' => $this->inviteEmails]);
        
        $this->dispatch('closeBulkInviteModal');
    }
    
    public function continueToNextStep()
    {
        // Calculate and update total travelers (organizer + invited)
        $totalTravelers = 1 + count($this->inviteEmails);
        
        // Update trip details in session
        $tripDetails = session('trip_details', []);
        $tripDetails['travelers'] = $totalTravelers;
        session(['trip_details' => $tripDetails]);
        
        // Save invites to session
        session(['trip_invites' => $this->inviteEmails]);
        
        // Log for debugging
        Log::info("Invites saved, dispatching specific event to move to review");
        
        // Use a specific named event
        $this->dispatch('completeInvitesStep');
    }
}