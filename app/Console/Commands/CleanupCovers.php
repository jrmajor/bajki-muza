<?php

namespace App\Console\Commands;

use App\Jobs\ProcessTaleCover;
use App\Models\Tale;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CleanupCovers extends Command
{
    protected $signature = 'cleanup:covers';

    protected $description = 'Remove unused tale covers.';

    public function handle(): int
    {
        $result = collect(Storage::cloud()->files('covers/original'))
            ->map(fn ($path) => $this->removeCoverIfUnused($path))
            ->contains(1) ? 1 : 0;

        return $this->getResponsiveSizes()
            ->map(fn ($size) => Storage::cloud()->files("covers/{$size}"))
            ->flatten()
            ->map(fn ($path) => Str::afterLast($path, '/'))
            ->unique()
            ->map(fn ($filename) => $this->removeCoverIfNoOriginal($filename))
            ->contains(1) ? 1 : $result;
    }

    protected function removeCoverIfUnused(string $path): int
    {
        $filename = Str::afterLast($path, '/');

        if (Tale::where('cover', $filename)->count() > 0) {
            return 0;
        }

        if (! $this->confirm("Delete {$filename}? (unused)", true)) {
            return 0;
        }

        $this->info("Removing (unused): {$filename}");

        return $this->removeResponsiveCovers($filename);
    }

    protected function removeCoverIfNoOriginal(string $filename): int
    {
        if (Storage::cloud()->exists("covers/original/{$filename}")) {
            return 0;
        }

        if (! $this->confirm("Delete {$filename}? (no original)", true)) {
            return 0;
        }

        $this->info("Removing (no original): {$filename}");

        return $this->removeResponsiveCovers($filename);
    }

    protected function removeResponsiveCovers($filename): int
    {
        Storage::cloud()->delete(
            $this->getResponsiveSizes()
            ->prepend('original')
            ->map(fn ($size) => "covers/{$size}/{$filename}")
            ->all()
        );

        return 0;
    }

    protected function getResponsiveSizes(): Collection
    {
        return collect(ProcessTaleCover::$sizes);
    }
}
