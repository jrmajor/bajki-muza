<?php

namespace App\Console\Commands;

use App\Images\Photo;
use Illuminate\Console\Command;
use Psl\Dict;
use Psl\Iter;
use Psl\Str;
use Psl\Type;
use Psl\Vec;

class CleanupPhotos extends Command
{
    protected $signature = 'cleanup:photos';

    protected $description = 'Remove unused artist photos.';

    public function handle(): int
    {
        $noModel = Vec\map(
            Photo::disk()->files('photos/original'),
            fn ($path) => $this->removePhotoIfItHasNoModel($path),
        );

        $variants = Vec\flat_map(
            Photo::sizes(),
            fn ($size) => Photo::disk()->files("photos/{$size}"),
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

    protected function removePhotoIfItHasNoModel(string $path): ExitCode
    {
        $filename = Str\after_last($path, '/');

        if (Photo::find($filename)) {
            return ExitCode::Ok;
        }

        if (! $this->confirm("Delete {$filename}? (unused)", true)) {
            return ExitCode::Ok;
        }

        $this->info("Removing (unused): {$filename}");

        return $this->deleteOriginalAndResponsiveVariants($filename);
    }

    protected function removeVariantsWithoutOriginal(string $filename): ExitCode
    {
        if (Photo::disk()->exists("photos/original/{$filename}")) {
            return ExitCode::Ok;
        }

        if (! $this->confirm("Delete {$filename}? (no original)", true)) {
            return ExitCode::Ok;
        }

        $this->info("Removing (no original): {$filename}");

        return $this->deleteOriginalAndResponsiveVariants($filename);
    }

    protected function deleteOriginalAndResponsiveVariants(string $filename): ExitCode
    {
        $photosToDelete = Vec\map(
            ['original', ...Photo::sizes()],
            fn ($size) => "photos/{$size}/{$filename}",
        );

        return Photo::disk()->delete($photosToDelete) ? ExitCode::Ok : ExitCode::Error;
    }
}
