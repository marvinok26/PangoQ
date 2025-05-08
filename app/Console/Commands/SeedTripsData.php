<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class SeedTripsData extends Command
{
    protected $signature = 'trips:seed';
    protected $description = 'Seed trips data in the correct order';

    public function handle()
    {
        $this->info('Seeding destinations...');
        $this->call('db:seed', ['--class' => 'Database\\Seeders\\DestinationSeeder']);
        
        $this->info('Seeding trip templates...');
        $this->call('db:seed', ['--class' => 'Database\\Seeders\\TripTemplateSeeder']);
        
        $this->info('Done!');
        
        // Show counts
        $destinationCount = DB::table('destinations')->count();
        $templateCount = DB::table('trip_templates')->count();
        $activityCount = DB::table('template_activities')->count();
        
        $this->info("Seeded $destinationCount destinations, $templateCount trip templates, and $activityCount template activities.");
        
        return 0;
    }
}