<?php

namespace App\Console\Commands;

use App\Images\Cover;
use App\Images\Exceptions\OriginalDoesNotExist;
use App\Models\Tale;
use Illuminate\Console\Command;
use Psl\Str;
use Psl\Type;

class ReprocessCovers extends Command
{
    protected $signature = 'reprocess:covers {--T|tale= : Slug for the tale to process}';

    protected $description = 'Remove responsive variants of tales covers and process them.';

    public function handle(): int
    {
        if ($this->option('tale') !== null) {
            return (int) ! $this->handleSingleTale();
        }

        if ($this->confirm('Do you want to reprocess all covers?', true)) {
            return (int) ! $this->handleAllTales();
        }

        return 1;
    }

    protected function handleSingleTale(): bool
    {
        $tale = Tale::firstWhere('slug', $this->option('tale'));

        if (! $tale) {
            $this->error('Tale does not exist.');

            return false;
        }

        if (! $tale->cover) {
            $this->error('Tale does not have a cover.');

            return false;
        }

        return $this->reprocessCover($tale->cover);
    }

    protected function handleAllTales(): bool
    {
        $tales = Tale::whereNotNull('cover_filename')->get();

        $total = $tales->count();

        return ! $tales->map(function ($tale, $index) use ($total) {
            $cover = Type\instance_of(Cover::class)->coerce($tale->cover);

            $this->info(Str\format(
                'Processing tale %d of %d: %s (%s)',
                ++$index, $total, $tale->title, $cover->filename(),
            ));

            return $this->reprocessCover($cover);
        })->contains(false);
    }

    protected function reprocessCover(Cover $cover): bool
    {
        if (count($missing = $cover->missingResponsiveVariants()) !== 0) {
            $missing = Str\join($missing, ', ');

            $this->warn("Some of responsive variants were missing ({$missing}).");
        }

        try {
            $cover->reprocess();

            return true;
        } catch (OriginalDoesNotExist $e) {
            $this->error("The original cover doesn't exist ({$e->path}).");

            return false;
        }
    }
}
