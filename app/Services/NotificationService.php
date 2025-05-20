<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\Trip;
use App\Models\User;
use App\Models\WalletTransaction;
use Illuminate\Support\Facades\Log;

class NotificationService
{
    /**
     * Send a trip invitation notification.
     * 
     * @param Trip $trip
     * @param string $email
     * @return void
     */
    public function sendTripInvitation(Trip $trip, string $email): void
    {
        try {
            // Check if user with this email exists
            $user = User::where('email', $email)->first();
            
            if ($user) {
                // Create notification for existing user
                Notification::create([
                    'user_id' => $user->id,
                    'type' => 'trip_invitation',
                    'title' => 'New Trip Invitation',
                    'message' => 'You have been invited to join "' . $trip->title . '" by ' . $trip->creator->name,
                ]);
                
                // Here you would also send an email notification
                // Mail::to($email)->send(new TripInvitation($trip));
                
                Log::info('Trip invitation notification created for user', [
                    'trip_id' => $trip->id,
                    'user_id' => $user->id
                ]);
            } else {
                // For non-users, we can only send an email
                // Mail::to($email)->send(new TripInvitation($trip));
                
                Log::info('Trip invitation email sent to non-user', [
                    'trip_id' => $trip->id,
                    'email' => $email
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Failed to send trip invitation notification', [
                'error' => $e->getMessage(),
                'trip_id' => $trip->id,
                'email' => $email
            ]);
        }
    }
    
    /**
     * Send notification when invitation is accepted.
     * 
     * @param Trip $trip
     * @param int $acceptingUserId
     * @return void
     */
    public function sendInvitationAccepted(Trip $trip, int $acceptingUserId): void
    {
        try {
            $acceptingUser = User::find($acceptingUserId);
            
            if (!$acceptingUser) {
                return;
            }
            
            // Notify trip creator
            Notification::create([
                'user_id' => $trip->creator_id,
                'type' => 'invitation_accepted',
                'title' => 'Trip Invitation Accepted',
                'message' => $acceptingUser->name . ' has accepted your invitation to "' . $trip->title . '"',
            ]);
            
            Log::info('Invitation accepted notification created', [
                'trip_id' => $trip->id,
                'user_id' => $trip->creator_id,
                'accepted_by' => $acceptingUserId
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to send invitation accepted notification', [
                'error' => $e->getMessage(),
                'trip_id' => $trip->id,
                'accepting_user_id' => $acceptingUserId
            ]);
        }
    }
    
    /**
     * Send deposit confirmation notification.
     * 
     * @param WalletTransaction $transaction
     * @return void
     */
    public function sendDepositConfirmation(WalletTransaction $transaction): void
    {
        try {
            $wallet = $transaction->wallet;
            $trip = $wallet->trip;
            
            Notification::create([
                'user_id' => $transaction->user_id,
                'type' => 'deposit_confirmation',
                'title' => 'Deposit Confirmed',
                'message' => 'Your deposit of $' . number_format($transaction->amount, 2) . ' for "' . $trip->title . '" has been confirmed.',
            ]);
            
            // Also notify trip creator if different from the depositor
            if ($trip->creator_id !== $transaction->user_id) {
                Notification::create([
                    'user_id' => $trip->creator_id,
                    'type' => 'deposit_made',
                    'title' => 'New Deposit Made',
                    'message' => User::find($transaction->user_id)->name . ' made a deposit of $' . number_format($transaction->amount, 2) . ' for "' . $trip->title . '".',
                ]);
            }
            
            Log::info('Deposit confirmation notification created', [
                'transaction_id' => $transaction->id,
                'user_id' => $transaction->user_id,
                'amount' => $transaction->amount
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to send deposit confirmation notification', [
                'error' => $e->getMessage(),
                'transaction_id' => $transaction->id
            ]);
        }
    }
    
    /**
     * Send withdrawal confirmation notification.
     * 
     * @param WalletTransaction $transaction
     * @return void
     */
    public function sendWithdrawalConfirmation(WalletTransaction $transaction): void
    {
        try {
            $wallet = $transaction->wallet;
            $trip = $wallet->trip;
            
            Notification::create([
                'user_id' => $transaction->user_id,
                'type' => 'withdrawal_confirmation',
                'title' => 'Withdrawal Confirmed',
                'message' => 'Your withdrawal of $' . number_format($transaction->amount, 2) . ' from "' . $trip->title . '" has been processed.',
            ]);
            
            // Also notify all trip members about the withdrawal
            foreach ($trip->members as $member) {
                if ($member->user_id !== $transaction->user_id) {
                    Notification::create([
                        'user_id' => $member->user_id,
                        'type' => 'withdrawal_made',
                        'title' => 'Trip Fund Withdrawal',
                        'message' => User::find($transaction->user_id)->name . ' withdrew $' . number_format($transaction->amount, 2) . ' from "' . $trip->title . '" savings.',
                    ]);
                }
            }
            
            Log::info('Withdrawal confirmation notification created', [
                'transaction_id' => $transaction->id,
                'user_id' => $transaction->user_id,
                'amount' => $transaction->amount
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to send withdrawal confirmation notification', [
                'error' => $e->getMessage(),
                'transaction_id' => $transaction->id
            ]);
        }
    }
    
    /**
     * Send payment reminder notification.
     * 
     * @param User $user
     * @param Trip $trip
     * @param float $suggestedAmount
     * @return void
     */
    public function sendPaymentReminder(User $user, Trip $trip, float $suggestedAmount): void
    {
        try {
            Notification::create([
                'user_id' => $user->id,
                'type' => 'payment_reminder',
                'title' => 'Trip Payment Reminder',
                'message' => 'Remember to contribute to your upcoming trip "' . $trip->title . '". Suggested amount: $' . number_format($suggestedAmount, 2),
            ]);
            
            Log::info('Payment reminder notification created', [
                'trip_id' => $trip->id,
                'user_id' => $user->id,
                'suggested_amount' => $suggestedAmount
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to send payment reminder notification', [
                'error' => $e->getMessage(),
                'trip_id' => $trip->id,
                'user_id' => $user->id
            ]);
        }
    }
    
    /**
     * Send trip start reminder notification.
     * 
     * @param User $user
     * @param Trip $trip
     * @param int $daysUntilTrip
     * @return void
     */
    public function sendTripStartReminder(User $user, Trip $trip, int $daysUntilTrip): void
    {
        try {
            Notification::create([
                'user_id' => $user->id,
                'type' => 'trip_reminder',
                'title' => 'Trip Starting Soon',
                'message' => 'Your trip "' . $trip->title . '" is starting in ' . $daysUntilTrip . ' days! Time to finalize your preparations.',
            ]);
            
            Log::info('Trip start reminder notification created', [
                'trip_id' => $trip->id,
                'user_id' => $user->id,
                'days_until_trip' => $daysUntilTrip
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to send trip start reminder notification', [
                'error' => $e->getMessage(),
                'trip_id' => $trip->id,
                'user_id' => $user->id
            ]);
        }
    }
}