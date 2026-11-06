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
            Photo::disk()->files(Photo::originalDirectory()),
            fn ($path) => $this->removePhotoIfItHasNoModel($path),
        );

        $variants = Vec\flat_map(
            Photo::variants(),
            fn ($variant) => Photo::disk()->files(Photo::variantDirectory($variant)),
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

        return $this->deleteOriginalAndVariants($filename);
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

        return $this->deleteOriginalAndVariants($filename);
    }

    protected function deleteOriginalAndVariants(string $filename): ExitCode
    {
        $photosToDelete = Vec\map(
            ['original', ...Photo::variants()],
            fn ($variant) => "photos/{$variant}/{$filename}",
        );

        return Photo::disk()->delete($photosToDelete) ? ExitCode::Ok : ExitCode::Error;
    }
}
