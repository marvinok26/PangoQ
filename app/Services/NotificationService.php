<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\Trip;
use App\Models\User;
use App\Models\WalletTransaction;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class NotificationService
{
    public function createNotification(int $userId, string $type, string $title, string $message): Notification
    {
        return Notification::create([
            'user_id' => $userId,
            'type' => $type,
            'title' => $title,
            'message' => $message,
        ]);
    }
    
    public function sendTripInvitation(Trip $trip, string $email): void
    {
        // Try to find user by email
        $user = User::where('email', $email)->first();
        
        if ($user) {
            // Create in-app notification
            $this->createNotification(
                $user->id,
                'trip_invitation',
                'Trip Invitation',
                "You've been invited to join \"{$trip->title}\" by {$trip->creator->name}."
            );
        }
        
        // Send email notification (this would be implemented with Laravel Mail)
        // For MVP, we'll just log this
        Log::info("Trip invitation email would be sent to {$email} for trip: {$trip->title}");
    }
    
    public function sendInvitationAccepted(Trip $trip, int $userId): void
    {
        $user = User::find($userId);
        
        if (!$user) {
            return;
        }
        
        // Notify trip creator
        $this->createNotification(
            $trip->creator_id,
            'invitation_accepted',
            'Invitation Accepted',
            "{$user->name} has accepted your invitation to \"{$trip->title}\"."
        );
    }
    
    public function sendDepositConfirmation(WalletTransaction $transaction): void
    {
        $wallet = $transaction->wallet;
        $trip = $wallet->trip;
        
        // Notify the user who made the deposit
        $this->createNotification(
            $transaction->user_id,
            'deposit_confirmation',
            'Deposit Confirmed',
            "Your deposit of \${$transaction->amount} for \"{$trip->title}\" has been confirmed."
        );
        
        // Notify trip creator if different from the user
        if ($trip->creator_id !== $transaction->user_id) {
            $this->createNotification(
                $trip->creator_id,
                'new_deposit',
                'New Deposit',
                "{$transaction->user->name} has deposited \${$transaction->amount} for \"{$trip->title}\"."
            );
        }
    }
    
    public function sendWithdrawalConfirmation(WalletTransaction $transaction): void
    {
        $wallet = $transaction->wallet;
        $trip = $wallet->trip;
        
        // Notify the user who made the withdrawal
        $this->createNotification(
            $transaction->user_id,
            'withdrawal_confirmation',
            'Withdrawal Confirmed',
            "Your withdrawal of \${$transaction->amount} from \"{$trip->title}\" has been processed."
        );
        
        // Notify trip creator if different from the user
        if ($trip->creator_id !== $transaction->user_id) {
            $this->createNotification(
                $trip->creator_id,
                'new_withdrawal',
                'New Withdrawal',
                "{$transaction->user->name} has withdrawn \${$transaction->amount} from \"{$trip->title}\"."
            );
        }
    }
    
    public function sendPaymentReminder(User $user, Trip $trip): void
    {
        $suggestedAmount = app(SavingsService::class)->calculateSuggestedContribution($trip->savingsWallet);
        
        $this->createNotification(
            $user->id,
            'payment_reminder',
            'Payment Reminder',
            "Don't forget to contribute \${$suggestedAmount} towards your \"{$trip->title}\" trip this week."
        );
    }
    
    public function getUnreadNotifications(int $userId, int $limit = 10)
    {
        return Notification::where('user_id', $userId)
            ->whereNull('read_at')
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }
    
    public function markAsRead(Notification $notification): void
    {
        $notification->markAsRead();
    }
}