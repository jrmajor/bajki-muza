<?php

namespace App\Console\Commands;

use App\Images\Cover;
use Illuminate\Console\Command;
use Psl\Dict;
use Psl\Iter;
use Psl\Str;
use Psl\Type;
use Psl\Vec;

class CleanupCovers extends Command
{
    protected $signature = 'cleanup:covers';

    protected $description = 'Remove unused tale covers.';

    public function handle(): int
    {
        $noModel = Vec\map(
            Cover::disk()->files('covers/original'),
            fn ($path) => $this->removeCoverIfItHasNoModel($path),
        );

        $variants = Vec\flat_map(
            Cover::sizes(),
            fn ($size) => Cover::disk()->files("covers/{$size}"),
        );
        $variants = Vec\map($variants, function (string $path) {
            return Type\string()->coerce(Str\after_last($path, '/'));
        });
        $variants = Vec\map(
            Dict\unique_scalar($variants),
            fn ($filename) => $this->removeVariantsWithoutOriginal($filename),
        );

        return Iter\contains([...$noModel, ...$variants], false) ? 1 : 0;
    }

    protected function removeCoverIfItHasNoModel(string $path): bool
    {
        $filename = Str\after_last($path, '/');

        if (Cover::find($filename)) {
            return true;
        }

        if (! $this->confirm("Delete {$filename}? (unused)", true)) {
            return true;
        }

        $this->info("Removing (unused): {$filename}");

        return $this->deleteOriginalAndResponsiveVariants($filename);
    }

    protected function removeVariantsWithoutOriginal(string $filename): bool
    {
        if (Cover::disk()->exists("covers/original/{$filename}")) {
            return true;
        }

        if (! $this->confirm("Delete {$filename}? (no original)", true)) {
            return true;
        }

        $this->info("Removing (no original): {$filename}");

        return $this->deleteOriginalAndResponsiveVariants($filename);
    }

    protected function deleteOriginalAndResponsiveVariants(string $filename): bool
    {
        $photosToDelete = Vec\map(
            ['original', ...Cover::sizes()],
            fn ($size) => "covers/{$size}/{$filename}",
        );

        return Cover::disk()->delete($photosToDelete);
    }
}
