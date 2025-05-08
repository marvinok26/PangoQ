<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Run destination seeder first
        $this->call(DestinationSeeder::class);
        
        // Then run trip template seeder (which requires destinations)
        $this->call(TripTemplateSeeder::class);
    }
}