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
            [...Cover::sizes(), ...Cover::variants()],
            fn ($size) => Cover::disk()->files("covers/{$size}"),
        );
        $variants = Vec\map($variants, function (string $path) {
            return Type\string()->coerce(Str\after_last($path, '/'));
        });
        $variants = Vec\map(
            Dict\unique_scalar($variants),
            fn ($filename) => $this->removeVariantsWithoutOriginal($filename),
        );

        return Iter\contains([...$noModel, ...$variants], ExitCode::Error) ? 1 : 0;
    }

    protected function removeCoverIfItHasNoModel(string $path): ExitCode
    {
        $filename = Str\after_last($path, '/');

        if (Cover::find($filename)) {
            return ExitCode::Ok;
        }

        if (! $this->confirm("Delete {$filename}? (unused)", true)) {
            return ExitCode::Ok;
        }

        $this->info("Removing (unused): {$filename}");

        return $this->deleteOriginalAndVariants($filename);
    }

    protected function removeVariantsWithoutOriginal(string $filename): ExitCode
    {
        if (Cover::disk()->exists("covers/original/{$filename}")) {
            return ExitCode::Ok;
        }

        if (! $this->confirm("Delete {$filename}? (no original)", true)) {
            return ExitCode::Ok;
        }

        $this->info("Removing (no original): {$filename}");

        return $this->deleteOriginalAndVariants($filename);
    }

    protected function deleteOriginalAndVariants(string $filename): ExitCode
    {
        $photosToDelete = Vec\map(
            ['original', ...Cover::sizes()],
            fn ($size) => "covers/{$size}/{$filename}",
        );

        return Cover::disk()->delete($photosToDelete) ? ExitCode::Ok : ExitCode::Error;
    }
}
