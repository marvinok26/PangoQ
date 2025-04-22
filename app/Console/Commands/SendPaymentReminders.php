<?php

namespace App\Console\Commands;

use App\Models\SavingsWallet;
use App\Models\TripMember;
use App\Services\NotificationService;
use Illuminate\Console\Command;

class SendPaymentReminders extends Command
{
    protected $signature = 'reminders:payments';
    
    protected $description = 'Send payment reminders to users based on their contribution schedule';
    
    protected NotificationService $notificationService;
    
    public function __construct(NotificationService $notificationService)
    {
        parent::__construct();
        $this->notificationService = $notificationService;
    }
    
    public function handle()
    {
        $this->info('Sending payment reminders...');
        
        // Get active wallets that have a future target date
        $wallets = SavingsWallet::whereHas('trip', function ($query) {
            $query->where('status', '!=', 'completed');
        })
        ->where('target_date', '>', now())
        ->where('current_amount', '<', \DB::raw('target_amount'))
        ->get();
        
        $count = 0;
        
        foreach ($wallets as $wallet) {
            $trip = $wallet->trip;
            
            // Get all accepted members of the trip
            $members = TripMember::where('trip_id', $trip->id)
                ->where('invitation_status', 'accepted')
                ->with('user')
                ->get();
                
            foreach ($members as $member) {
                if (!$member->user) {
                    continue;
                }
                
                // Check if reminder is due today based on contribution frequency
                $shouldRemind = false;
                
                if ($wallet->contribution_frequency === 'weekly' && now()->dayOfWeek === 1) { // Monday
                    $shouldRemind = true;
                } elseif ($wallet->contribution_frequency === 'monthly' && now()->day === 1) { // First day of month
                    $shouldRemind = true;
                }
                
                if ($shouldRemind) {
                    $this->notificationService->sendPaymentReminder($member->user, $trip);
                    $count++;
                }
            }
        }
        
        $this->info("Successfully sent {$count} payment reminders.");
        
        return 0;
    }
}