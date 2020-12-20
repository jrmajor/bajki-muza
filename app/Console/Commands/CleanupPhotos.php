<?php

namespace App\Console\Commands;

use App\Images\Photo;
use App\Models\Artist;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CleanupPhotos extends Command
{
    protected $signature = 'cleanup:photos';

    protected $description = 'Remove unused artist photos.';

    public function handle(): int
    {
        $result = collect(Storage::cloud()->files('photos/original'))
            ->map(fn ($path) => $this->removePhotoIfUnused($path))
            ->contains(1) ? 1 : 0;

        return Photo::sizes()
            ->map(fn ($size) => Storage::cloud()->files("photos/{$size}"))
            ->flatten()
            ->map(fn ($path) => Str::afterLast($path, '/'))
            ->unique()
            ->map(fn ($filename) => $this->removePhotoIfNoOriginal($filename))
            ->contains(1) ? 1 : $result;
    }

    protected function removePhotoIfUnused(string $path): int
    {
        $filename = Str::afterLast($path, '/');

        if (Artist::where('photo', $filename)->count() > 0) {
            return 0;
        }

        if (! $this->confirm("Delete {$filename}? (unused)", true)) {
            return 0;
        }

        $this->info("Removing (unused): {$filename}");

        return $this->deleteResponsiveVariants($filename);
    }

    protected function removePhotoIfNoOriginal(string $filename): int
    {
        if (Storage::cloud()->exists("photos/original/{$filename}")) {
            return 0;
        }

        if (! $this->confirm("Delete {$filename}? (no original)", true)) {
            return 0;
        }

        $this->info("Removing (no original): {$filename}");

        return $this->deleteResponsiveVariants($filename);
    }

    protected function deleteResponsiveVariants($filename): int
    {
        $photosToDelete = Photo::sizes()
            ->prepend('original')
            ->map(fn ($size) => "photos/{$size}/{$filename}")
            ->all();

        Storage::cloud()->delete($photosToDelete);

        return 0;
    }
}
