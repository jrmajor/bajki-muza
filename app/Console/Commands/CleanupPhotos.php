<?php

namespace App\Console\Commands;

use App\Jobs\ProcessArtistPhoto;
use App\Models\Artist;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;
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

        return $this->getResponsiveSizes()
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

        return $this->removeResponsivePhotos($filename);
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

        return $this->removeResponsivePhotos($filename);
    }

    protected function removeResponsivePhotos($filename): int
    {
        Storage::cloud()->delete(
            $this->getResponsiveSizes()
            ->prepend('original')
            ->map(fn ($size) => "photos/{$size}/{$filename}")
            ->all()
        );

        return 0;
    }

    protected function getResponsiveSizes(): Collection
    {
        return collect(ProcessArtistPhoto::$faceSizes)
            ->concat(ProcessArtistPhoto::$imageSizes);
    }
}
