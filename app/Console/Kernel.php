<?php

namespace App\Console;

use App\Jobs\RefreshArtistsCache;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected function schedule(Schedule $schedule): void
    {
        // At 03:00 on Tuesday and Friday.
        $schedule->job(RefreshArtistsCache::class)->cron('0 3 * * 2,5');
    }

    protected function commands(): void
    {
        $this->load(__DIR__ . '/Commands');
    }
}
