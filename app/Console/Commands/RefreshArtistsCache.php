<?php

namespace App\Console\Commands;

use App\Models\Artist;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Psl\Math;
use Throwable;

#[Signature('artist-cache:refresh')]
#[Description('Refresh cached data from external services.')]
final class RefreshArtistsCache extends Command
{
    public function handle(): int
    {
        $time = microtime(true);
        $count = 0;
        $failed = 0;

        Artist::lazy(20)->each(function (Artist $a) use (&$count, &$failed) {
            $this->line("Refreshing cache for {$a->name}...");

            try {
                $a->refreshCache();

                $count++;
            } catch (Throwable $e) {
                report($e);

                $this->error("Failed to refresh cache for {$a->name}: {$e->getMessage()}");

                $failed++;
            }
        });

        $time = Math\round(microtime(true) - $time, 2);

        $this->comment("Refreshed cache for {$count} artists in {$time} seconds");

        if ($failed > 0) {
            $this->error("Failed to refresh cache for {$failed} artists");

            return self::FAILURE;
        }

        return self::SUCCESS;
    }
}
