<?php

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
        // Sync service prices from API every hour
        $schedule->command('services:sync-prices')
            ->hourly()
            ->withoutOverlapping()
            ->runInBackground();
        
        // Refresh exchange rate every 30 minutes
        $schedule->call(function () {
            \App\Services\ExchangeRateService::refresh();
        })->everyThirtyMinutes();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
