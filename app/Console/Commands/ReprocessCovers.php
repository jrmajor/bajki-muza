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

    protected $description = 'Remove variants of tales covers and process them.';

    public function handle(): int
    {
        if ($this->option('tale') !== null) {
            return $this->handleSingleTale()->value;
        }

        if ($this->confirm('Do you want to reprocess all covers?', true)) {
            return $this->handleAllTales()->value;
        }

        return 1;
    }

    protected function handleSingleTale(): ExitCode
    {
        $tale = Tale::firstWhere('slug', $this->option('tale'));

        if (! $tale) {
            $this->error('Tale does not exist.');

            return ExitCode::Error;
        }

        if (! $tale->cover) {
            $this->error('Tale does not have a cover.');

            return ExitCode::Error;
        }

        return $this->reprocessCover($tale->cover);
    }

    protected function handleAllTales(): ExitCode
    {
        $tales = Tale::whereNotNull('cover_filename')->get();

        $total = $tales->count();

        return $tales->map(function ($tale, $index) use ($total) {
            $cover = Type\instance_of(Cover::class)->coerce($tale->cover);

            $this->info(Str\format(
                'Processing tale %d of %d: %s (%s)',
                ++$index, $total, $tale->title, $cover->filename(),
            ));

            return $this->reprocessCover($cover);
        })->contains(ExitCode::Error) ? ExitCode::Error : ExitCode::Ok;
    }

    protected function reprocessCover(Cover $cover): ExitCode
    {
        if (count($missing = $cover->missingVariants()) !== 0) {
            $missing = implode(', ', $missing);

            $this->warn("Some variants were missing ({$missing}).");
        }

        try {
            $cover->reprocess();

            return ExitCode::Ok;
        } catch (OriginalDoesNotExist $e) {
            $this->error("The original cover doesn't exist ({$e->path}).");

            return ExitCode::Error;
        }
    }
}
