<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Artisan;

class FixImagesAndReseed extends Command
{
    protected $signature = 'fix:images-reseed {--force : Force the operation without confirmation}';
    protected $description = 'Fix image URLs by wiping data and reseeding with correct image paths';

    public function handle()
    {
        if (!$this->option('force') && !$this->confirm('WARNING: This will DELETE ALL your data and reseed the database. Are you sure?')) {
            $this->info('Operation cancelled.');
            return 1;
        }

        $this->info('Starting database reset and reseeding...');
        
        // Step 1: Reset database tables in the correct order to avoid foreign key constraints
        $this->info('Truncating tables...');
        
        // Disable foreign key checks temporarily
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        
        // Clean up tables in reverse dependency order
        $tables = [
            'template_activities',
            'trip_templates',
            'destinations',
        ];
        
        foreach ($tables as $table) {
            if (Schema::hasTable($table)) {
                $this->line("Truncating table: {$table}");
                DB::table($table)->truncate();
            }
        }
        
        // Re-enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        
        // Step 2: Run seeders in correct order
        $this->info('Running seeders with correct image URLs...');
        
        $this->line('Seeding destinations...');
        Artisan::call('db:seed', [
            '--class' => 'Database\\Seeders\\DestinationSeeder',
        ]);
        $this->line(Artisan::output());
        
        $this->line('Seeding trip templates...');
        Artisan::call('db:seed', [
            '--class' => 'Database\\Seeders\\TripTemplateSeeder',
        ]);
        $this->line(Artisan::output());
        
        // Step 3: Verify the results
        $destinationCount = DB::table('destinations')->count();
        $templateCount = DB::table('trip_templates')->count();
        $activityCount = DB::table('template_activities')->count();
        
        $this->info('Successfully reseeded database with correct image URLs!');
        $this->info("Seeded $destinationCount destinations, $templateCount trip templates, and $activityCount template activities.");
        
        // Step 4: Verify image URLs are not null
        $nullDestImages = DB::table('destinations')->whereNull('image_url')->count();
        $nullActivityImages = DB::table('template_activities')->whereNull('image_url')->count();
        
        if ($nullDestImages > 0 || $nullActivityImages > 0) {
            $this->warn("WARNING: Found $nullDestImages destinations and $nullActivityImages template activities with NULL image_url values.");
            
            if ($this->confirm('Would you like to double-check the models to ensure image_url is fillable?')) {
                $this->line('Checking models...');
                
                // Destination model check
                $destModel = new \App\Models\Destination();
                $this->line('Destination fillable: ' . implode(', ', $destModel->getFillable()));
                
                // TemplateActivity model check
                $activityModel = new \App\Models\TemplateActivity();
                $this->line('TemplateActivity fillable: ' . implode(', ', $activityModel->getFillable()));
            }
        } else {
            $this->info('All image URLs have been correctly set!');
        }
        
        // Step 5: Clear caches
        if ($this->confirm('Would you like to clear application caches?')) {
            $this->call('cache:clear');
            $this->call('view:clear');
            $this->call('route:clear');
            $this->call('config:clear');
            $this->call('optimize:clear');
            $this->info('All caches cleared successfully!');
        }
        
        return 0;
    }
}