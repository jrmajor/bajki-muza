<?php

namespace App\Console\Commands;

use App\Jobs\ProcessTaleCover;
use App\Models\Tale;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class ReprocessCovers extends Command
{
    protected $signature = 'reprocess:covers {--T|tale= : Slug for the tale to process}';

    protected $description = 'Remove responsive variants of tales covers and process them.';

    public function handle(): int
    {
        if ($this->option('tale') !== null) {
            return $this->handleSingleTale();
        } elseif($this->confirm('Do you want to reprocess all covers?', true)) {
            return $this->handleAllTales();
        } else {
            return 1;
        }
    }

    protected function handleSingleTale(): int
    {
        $tale = Tale::where('slug', $this->option('tale'))->first();

        if (! $tale) {
            $this->error('Tale doesn\'t exist.');
            return 1;
        }

        if ($tale->cover === null) {
            $this->error('Tale doesn\'t have a cover.');
            return 1;
        }

        return $this->reprocessTale($tale);
    }

    protected function handleAllTales(): int
    {
        $tales = Tale::whereNotNull('cover')->get();

        $total = $tales->count();

        return $tales
            ->map(function ($tale, $index) use ($total) {
                $index++;
                $this->info("Processing tale {$index} of {$total}: {$tale->title} ({$tale->cover})");
                return $this->reprocessTale($tale);
            })
            ->contains(1) ? 1 : 0;
    }

    protected function reprocessTale(Tale $tale): int
    {
        if (Storage::cloud()->missing("covers/original/{$tale->cover}")) {
            $this->error("The original cover doesn't exist.");
            return 1;
        }

        if (! $this->deleteResponsiveVariants($tale)) {
            $this->warn('Some of responsive images were missing. Fixing that!');
        }

        ProcessTaleCover::dispatchSync($tale);

        return 0;
    }

    protected function deleteResponsiveVariants(Tale $tale): bool
    {
        return Storage::cloud()->delete(
            collect($this->getResponsiveSizes())
            ->map(fn ($size) => "covers/{$size}/{$tale->cover}")
            ->all()
        );
    }

    protected function getResponsiveSizes(): array
    {
        return ProcessTaleCover::$sizes;
    }
}
