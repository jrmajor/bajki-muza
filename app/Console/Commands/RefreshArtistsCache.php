<?php

namespace App\Console\Commands;

use App\Models\Artist;
use Illuminate\Console\Command;
use Psl\Math;

final class RefreshArtistsCache extends Command
{
    protected $signature = 'artist-cache:refresh';

    protected $description = 'Remove unused artist photos.';

    public function handle(): void
    {
        $time = microtime(true);
        $count = 0;

        Artist::lazy(20)->each(function (Artist $a) use (&$count) {
            $this->line("Refreshing cache for {$a->name}...");

            $a->refreshCache();

            $count++;
        });

        $time = Math\round(microtime(true) - $time, 2);

        $this->comment("Refreshed cache for {$count} artists in {$time} seconds");
    }
}
