<?php

namespace App\Console\Commands;

use App\Models\Trip;
use Illuminate\Console\Command;

class CleanupExpiredTrips extends Command
{
    protected $signature = 'trips:cleanup';
    
    protected $description = 'Update trip statuses based on their dates';
    
    public function __construct()
    {
        parent::__construct();
    }
    
    public function handle()
    {
        $this->info('Updating trip statuses...');
        
        // Mark trips with end_date in the past as 'completed'
        $completedCount = Trip::where('status', '!=', 'completed')
            ->where('end_date', '<', now()->toDateString())
            ->update(['status' => 'completed']);
            
        $this->info("{$completedCount} trips marked as completed.");
        
        // Mark trips with start_date in the past and end_date in the future as 'active'
        $activeCount = Trip::where('status', 'planning')
            ->where('start_date', '<=', now()->toDateString())
            ->where('end_date', '>=', now()->toDateString())
            ->update(['status' => 'active']);
            
        $this->info("{$activeCount} trips marked as active.");
        
        return 0;
    }
}