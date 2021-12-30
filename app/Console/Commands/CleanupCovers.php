<?php

namespace App\Console\Commands;

use App\Images\Cover;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class CleanupCovers extends Command
{
    protected $signature = 'cleanup:covers';

    protected $description = 'Remove unused tale covers.';

    public function handle(): int
    {
        $result = collect(Cover::disk()->files('covers/original'))
            ->map(fn (string $path) => $this->removeCoverIfItHasNoModel($path))
            ->contains(1) ? 1 : 0;

        return collect(Cover::sizes())
            ->map(fn (int $size) => Cover::disk()->files("covers/{$size}"))
            ->flatten()
            ->map(fn (string $path) => Str::afterLast($path, '/'))
            ->unique()
            ->map(fn (string $filename) => $this->removeVariantsWithoutOriginal($filename))
            ->contains(1) ? 1 : $result;
    }

    protected function removeCoverIfItHasNoModel(string $path): int
    {
        $filename = Str::afterLast($path, '/');

        if (Cover::find($filename)) {
            return 0;
        }

        if (! $this->confirm("Delete {$filename}? (unused)", true)) {
            return 0;
        }

        $this->info("Removing (unused): {$filename}");

        return $this->deleteOriginalAndResponsiveVariants($filename);
    }

    protected function removeVariantsWithoutOriginal(string $filename): int
    {
        if (Cover::disk()->exists("covers/original/{$filename}")) {
            return 0;
        }

        if (! $this->confirm("Delete {$filename}? (no original)", true)) {
            return 0;
        }

        $this->info("Removing (no original): {$filename}");

        return $this->deleteOriginalAndResponsiveVariants($filename);
    }

    protected function deleteOriginalAndResponsiveVariants(string $filename): int
    {
        $coversToDelete = collect(Cover::sizes())
            ->prepend('original')
            ->map(fn ($size) => "covers/{$size}/{$filename}")
            ->all();

        Cover::disk()->delete($coversToDelete);

        return 0;
    }
}
