<?php

declare(strict_types=1);

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // Runs your command hourly
        $schedule->command('waste:cancel-organic-stale')->hourly();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        // Load classes from app/Console/Commands (artisan make:command)
        $this->load(__DIR__ . '/Commands');

        // Optional: keeps inline command definitions in routes/console.php
        require base_path('routes/console.php');
    }
}
