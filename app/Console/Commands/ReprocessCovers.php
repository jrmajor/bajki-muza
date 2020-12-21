<?php

namespace App\Console\Commands;

use App\Images\Exceptions\OriginalDoesNotExist;
use App\Models\Tale;
use Illuminate\Console\Command;

class ReprocessCovers extends Command
{
    protected $signature = 'reprocess:covers {--T|tale= : Slug for the tale to process}';

    protected $description = 'Remove responsive variants of tales covers and process them.';

    public function handle(): int
    {
        if ($this->option('tale') !== null) {
            return $this->handleSingleTale();
        }

        if ($this->confirm('Do you want to reprocess all covers?', true)) {
            return $this->handleAllTales();
        }

        return 1;
    }

    protected function handleSingleTale(): int
    {
        $tale = Tale::where('slug', $this->option('tale'))->first();

        if (! $tale) {
            $this->error('Tale does not exist.');

            return 1;
        }

        if ($tale->cover === null) {
            $this->error('Tale does not have a cover.');

            return 1;
        }

        return $this->reprocessTale($tale);
    }

    protected function handleAllTales(): int
    {
        $tales = Tale::whereNotNull('cover_filename')->get();

        $total = $tales->count();

        return $tales
            ->map(function ($tale, $index) use ($total) {
                $index++;
                $this->info("Processing tale {$index} of {$total}: {$tale->title} ({$tale->cover->filename()})");

                return $this->reprocessTale($tale);
            })
            ->contains(1) ? 1 : 0;
    }

    protected function reprocessTale(Tale $tale): int
    {
        if (($missing = $tale->cover->missingResponsiveVariants())->isNotEmpty()) {
            $missing = $missing->join(', ');

            $this->warn("Some of responsive variants were missing ({$missing}).");
        }

        try {
            $tale->cover->reprocess();

            return 0;
        } catch (OriginalDoesNotExist $exception) {
            $this->error("The original cover doesn't exist ({$exception->path}).");

            return 1;
        }
    }
}
