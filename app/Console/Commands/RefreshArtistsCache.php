<?php

namespace App\Console\Commands;

use App\Models\Artist;
use Illuminate\Console\Command;

final class RefreshArtistsCache extends Command
{
    protected $signature = 'artist-cache:refresh';

    protected $description = 'Remove unused artist photos.';

    public function handle(): void
    {
        Artist::lazy(20)->each(function (Artist $a) {
            $this->line("Refreshing cache for {$a->name}...");

            $a->refreshCache();
        });
    }
}
