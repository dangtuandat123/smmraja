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
        // Sync order status from API every 2 minutes
        $schedule->command('orders:sync-status')
            ->everyTwoMinutes()
            ->withoutOverlapping()
            ->runInBackground();
        
        // Sync service prices from API every 30 minutes
        $schedule->command('services:sync-prices')
            ->everyThirtyMinutes()
            ->withoutOverlapping()
            ->runInBackground();
        
        // Send Telegram notifications every minute
        $schedule->command('telegram:send')
            ->everyMinute()
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
