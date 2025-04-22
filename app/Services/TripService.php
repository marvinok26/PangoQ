<?php

namespace App\Services;

use App\Models\Itinerary;
use App\Models\SavingsWallet;
use App\Models\Trip;
use App\Models\TripMember;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class TripService
{
    protected NotificationService $notificationService;
    
    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }
    
    public function createTrip(array $data, int $userId): Trip
    {
        return DB::transaction(function () use ($data, $userId) {
            // Create the trip
            $trip = Trip::create([
                'creator_id' => $userId,
                'title' => $data['title'],
                'description' => $data['description'] ?? null,
                'destination' => $data['destination'],
                'start_date' => $data['start_date'],
                'end_date' => $data['end_date'],
                'budget' => $data['budget'] ?? null,
                'status' => 'planning',
            ]);
            
            // Add creator as a trip member with organizer role
            TripMember::create([
                'trip_id' => $trip->id,
                'user_id' => $userId,
                'role' => 'organizer',
                'invitation_status' => 'accepted',
            ]);
            
            // Create a savings wallet for the trip
            $walletName = 'Savings for ' . $trip->title;
            $targetAmount = $data['budget'] ?? 0;
            
            SavingsWallet::create([
                'trip_id' => $trip->id,
                'name' => $walletName,
                'target_amount' => $targetAmount,
                'current_amount' => 0,
                'target_date' => $data['end_date'],
                'contribution_frequency' => 'weekly',
            ]);
            
            // Create empty itineraries for each day of the trip
            $startDate = Carbon::parse($data['start_date']);
            $endDate = Carbon::parse($data['end_date']);
            $dayNumber = 1;
            
            for ($date = $startDate; $date->lte($endDate); $date->addDay()) {
                Itinerary::create([
                    'trip_id' => $trip->id,
                    'title' => 'Day ' . $dayNumber . ' - ' . $date->format('F j, Y'),
                    'description' => '',
                    'day_number' => $dayNumber,
                    'date' => $date->toDateString(),
                ]);
                
                $dayNumber++;
            }
            
            return $trip;
        });
    }
    
    public function inviteMembers(Trip $trip, array $emails): void
    {
        foreach ($emails as $email) {
            // Check if invitation already exists
            $existingInvitation = TripMember::where('trip_id', $trip->id)
                ->where('invitation_email', $email)
                ->first();
                
            if ($existingInvitation) {
                continue;
            }
            
            TripMember::create([
                'trip_id' => $trip->id,
                'invitation_email' => $email,
                'role' => 'member',
                'invitation_status' => 'pending',
            ]);
            
            // Send notification through NotificationService
            $this->notificationService->sendTripInvitation($trip, $email);
        }
    }
    
    public function acceptInvitation(TripMember $invitation, int $userId): void
    {
        $invitation->update([
            'user_id' => $userId,
            'invitation_status' => 'accepted',
        ]);
        
        // Notify trip creator that someone accepted the invitation
        $this->notificationService->sendInvitationAccepted($invitation->trip, $userId);
    }
    
    public function updateTripStatus(Trip $trip, string $status): void
    {
        $trip->update(['status' => $status]);
    }
}