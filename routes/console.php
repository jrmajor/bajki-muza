<?php

use App\Console\Commands;
use Illuminate\Console\Scheduling\Schedule as RealSchedule;
use Illuminate\Support\Facades\Schedule;

Schedule::command(Commands\RefreshArtistsCache::class)
    ->days([RealSchedule::TUESDAY, RealSchedule::FRIDAY])
    ->at('03:00')
    ->graceTimeInMinutes(30);
