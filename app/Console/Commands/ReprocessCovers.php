<?php

namespace App\Console\Commands;

use App\Images\Cover;
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
        $tale = Tale::firstWhere('slug', $this->option('tale'));

        if (! $tale) {
            $this->error('Tale does not exist.');

            return 1;
        }

        if (! $tale->cover) {
            $this->error('Tale does not have a cover.');

            return 1;
        }

        return $this->reprocessCover($tale->cover);
    }

    protected function handleAllTales(): int
    {
        $tales = Tale::whereNotNull('cover_filename')->get();

        $total = $tales->count();

        return $tales
            ->map(function ($tale, $index) use ($total) {
                assert($tale->cover !== null);

                $index++;
                $this->info("Processing tale {$index} of {$total}: {$tale->title} ({$tale->cover->filename()})");

                return $this->reprocessCover($tale->cover);
            })
            ->contains(1) ? 1 : 0;
    }

    protected function reprocessCover(Cover $cover): int
    {
        if (count($missing = $cover->missingResponsiveVariants()) !== 0) {
            $missing = implode(', ', $missing);

            $this->warn("Some of responsive variants were missing ({$missing}).");
        }

        try {
            $cover->reprocess();

            return 0;
        } catch (OriginalDoesNotExist $exception) {
            $this->error("The original cover doesn't exist ({$exception->path}).");

            return 1;
        }
    }
}
