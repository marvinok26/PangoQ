<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        Commands\CreateSuperAdminCommand::class,
    ];

    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // Send payment reminders daily at 8 AM
        $schedule->command('reminders:payments')->dailyAt('08:00');
        
        // Update trip statuses daily at midnight
        $schedule->command('trips:cleanup')->dailyAt('00:00');
        
        // Clean up old activity logs (keep last 90 days) - runs daily at 2 AM
        $schedule->command('model:prune', ['--model' => [\App\Models\ActivityLog::class]])
                 ->dailyAt('02:00')
                 ->description('Clean up old activity logs');
        
        // Generate admin reports weekly on Monday at 6 AM
        $schedule->command('admin:generate-weekly-report')
                 ->weeklyOn(1, '06:00')
                 ->description('Generate weekly admin reports');
                 
        // Clean up failed login attempts older than 30 days
        $schedule->command('model:prune', ['--model' => [\App\Models\LoginAttempt::class]])
                 ->weekly()
                 ->description('Clean up old login attempts');
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

