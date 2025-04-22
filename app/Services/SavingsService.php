<?php

namespace App\Services;

use App\Exceptions\InsufficientFundsException;
use App\Models\SavingsWallet;
use App\Models\WalletTransaction;
use Illuminate\Support\Facades\DB;

class SavingsService
{
    protected NotificationService $notificationService;
    
    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }
    
    public function deposit(SavingsWallet $wallet, int $userId, float $amount, string $paymentMethod = null): WalletTransaction
    {
        return DB::transaction(function () use ($wallet, $userId, $amount, $paymentMethod) {
            // Create transaction record
            $transaction = WalletTransaction::create([
                'wallet_id' => $wallet->id,
                'user_id' => $userId,
                'type' => 'deposit',
                'amount' => $amount,
                'status' => 'completed',
                'payment_method' => $paymentMethod,
                'transaction_reference' => 'DEP-' . uniqid(),
            ]);
            
            // Update wallet balance
            $wallet->increment('current_amount', $amount);
            
            // Send notification
            $this->notificationService->sendDepositConfirmation($transaction);
            
            return $transaction;
        });
    }
    
    public function withdraw(SavingsWallet $wallet, int $userId, float $amount): WalletTransaction
    {
        return DB::transaction(function () use ($wallet, $userId, $amount) {
            // Check if wallet has sufficient funds
            if ($wallet->current_amount < $amount) {
                throw new InsufficientFundsException('Insufficient funds in wallet');
            }
            
            // Create transaction record
            $transaction = WalletTransaction::create([
                'wallet_id' => $wallet->id,
                'user_id' => $userId,
                'type' => 'withdrawal',
                'amount' => $amount,
                'status' => 'completed',
                'transaction_reference' => 'WIT-' . uniqid(),
            ]);
            
            // Update wallet balance
            $wallet->decrement('current_amount', $amount);
            
            // Send notification
            $this->notificationService->sendWithdrawalConfirmation($transaction);
            
            return $transaction;
        });
    }
    
    public function calculateSuggestedContribution(SavingsWallet $wallet): float
    {
        $remainingAmount = $wallet->remaining_amount;
        
        if ($remainingAmount <= 0) {
            return 0;
        }
        
        $today = now();
        $targetDate = $wallet->target_date;
        
        // If target date is past or today, return the full remaining amount
        if ($today->greaterThanOrEqualTo($targetDate)) {
            return $remainingAmount;
        }
        
        $weeksRemaining = $today->diffInWeeks($targetDate);
        
        if ($weeksRemaining <= 0) {
            $weeksRemaining = 1;
        }
        
        if ($wallet->contribution_frequency === 'weekly') {
            return round($remainingAmount / $weeksRemaining, 2);
        } else {
            // Monthly contribution
            $monthsRemaining = max(1, ceil($weeksRemaining / 4));
            return round($remainingAmount / $monthsRemaining, 2);
        }
    }
    
    public function updateWalletSettings(SavingsWallet $wallet, array $data): SavingsWallet
    {
        $wallet->update([
            'name' => $data['name'] ?? $wallet->name,
            'target_amount' => $data['target_amount'] ?? $wallet->target_amount,
            'target_date' => $data['target_date'] ?? $wallet->target_date,
            'contribution_frequency' => $data['contribution_frequency'] ?? $wallet->contribution_frequency,
        ]);
        
        return $wallet;
    }
    
    public function getTransactionHistory(SavingsWallet $wallet, int $limit = 10)
    {
        return $wallet->transactions()
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->paginate($limit);
    }
}