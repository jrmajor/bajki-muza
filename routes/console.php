<?php

use App\Console\Commands;
use Illuminate\Console\Scheduling\Schedule as RealSchedule;
use Illuminate\Support\Facades\Schedule;
use Laravel\Telescope\Console\PruneCommand as PruneTelescopeEntries;

Schedule::command(Commands\RefreshArtistsCache::class)
    ->days([RealSchedule::TUESDAY, RealSchedule::FRIDAY])
    ->at('03:00')
    ->graceTimeInMinutes(30);

Schedule::command(PruneTelescopeEntries::class)->dailyAt('01:30');
Schedule::command(Commands\MakeBackup::class)->dailyAt('02:00');
