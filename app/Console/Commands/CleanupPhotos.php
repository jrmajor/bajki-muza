<?php

namespace App\Console\Commands;

use App\Images\Photo;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class CleanupPhotos extends Command
{
    protected $signature = 'cleanup:photos';

    protected $description = 'Remove unused artist photos.';

    public function handle(): int
    {
        $result = collect(Photo::disk()->files('photos/original'))
            ->map(fn ($path) => $this->removePhotoIfItHasNoModel($path))
            ->contains(1) ? 1 : 0;

        return Photo::sizes()
            ->map(fn (int $size) => Photo::disk()->files("photos/{$size}"))
            ->flatten()
            ->map(fn (string $path) => Str::afterLast($path, '/'))
            ->unique()
            ->map(fn (string $filename) => $this->removeVariantsWithoutOriginal($filename))
            ->contains(1) ? 1 : $result;
    }

    protected function removePhotoIfItHasNoModel(string $path): int
    {
        $filename = Str::afterLast($path, '/');

        if (Photo::find($filename)) {
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
        if (Photo::disk()->exists("photos/original/{$filename}")) {
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
        $photosToDelete = Photo::sizes()
            ->prepend('original')
            ->map(fn ($size) => "photos/{$size}/{$filename}")
            ->all();

        Photo::disk()->delete($photosToDelete);

        return 0;
    }
}
