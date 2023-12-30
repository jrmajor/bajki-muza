<?php

namespace App\Console;

use App\Console\Commands\RefreshArtistsCache;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected function schedule(Schedule $schedule): void
    {
        // At 03:00 on Tuesday and Friday.
        $schedule->command(RefreshArtistsCache::class)
            ->days([Schedule::TUESDAY, Schedule::FRIDAY])
            ->at('03:00')
            ->graceTimeInMinutes(30);
    }

    protected function commands(): void
    {
        $this->load(__DIR__ . '/Commands');
    }
}
