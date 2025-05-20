<?php

namespace App\Console\Commands;

use App\Models\Trip;
use App\Models\TripMember;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class FixTripMembers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:fix-trip-members';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fix missing trip members by adding creators as members';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting to fix trip members...');
        
        // Get all trips
        $trips = Trip::all();
        $this->info("Found {$trips->count()} trips total");
        
        $counter = 0;
        
        // Process each trip
        foreach ($trips as $trip) {
            // Check if the creator is already a member
            $existingMember = TripMember::where('trip_id', $trip->id)
                ->where('user_id', $trip->creator_id)
                ->first();
                
            if (!$existingMember) {
                // Add the creator as a member
                TripMember::create([
                    'trip_id' => $trip->id,
                    'user_id' => $trip->creator_id,
                    'role' => 'organizer',
                    'invitation_status' => 'accepted'
                ]);
                
                $counter++;
                $this->info("Added creator (User ID: {$trip->creator_id}) as member for Trip ID: {$trip->id}");
            }
        }
        
        $this->info("Fixed $counter trips by adding creators as members");
        $this->info('All trips have been processed.');
        
        return Command::SUCCESS;
    }
}