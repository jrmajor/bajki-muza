<?php

namespace App\Console\Commands;

use App\Jobs\ProcessArtistPhoto;
use App\Models\Artist;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class ReprocessPhotos extends Command
{
    protected $signature = 'reprocess:photos {--A|artist= : Slug for the artist to process}';

    protected $description = 'Remove responsive variants of artists photos and process them.';

    public function handle(): int
    {
        if ($this->option('artist') !== null) {
            return $this->handleSingleArtist();
        } elseif ($this->confirm('Do you want to reprocess all photos?', true)) {
            return $this->handleAllArtists();
        } else {
            return 1;
        }
    }

    protected function handleSingleArtist(): int
    {
        $artist = Artist::where('slug', $this->option('artist'))->first();

        if (! $artist) {
            $this->error('Artist doesn\'t exist.');

            return 1;
        }

        if ($artist->photo === null) {
            $this->error('Artist doesn\'t have a photo.');

            return 1;
        }

        return $this->reprocessArtist($artist);
    }

    protected function handleAllArtists(): int
    {
        $artists = Artist::whereNotNull('photo')->get();

        $total = $artists->count();

        return $artists
            ->map(function ($artist, $index) use ($total) {
                $index++;
                $this->info("Processing artist {$index} of {$total}: {$artist->name} ({$artist->photo})");

                return $this->reprocessArtist($artist);
            })
            ->contains(1) ? 1 : 0;
    }

    protected function reprocessArtist(Artist $artist): int
    {
        if (Storage::cloud()->missing("photos/original/{$artist->photo}")) {
            $this->error("The original photo doesn't exist.");

            return 1;
        }

        if (! $this->deleteResponsiveVariants($artist)) {
            $this->warn('Some of responsive images were missing. Fixing that!');
        }

        ProcessArtistPhoto::dispatchSync($artist, $artist->photo, $artist->photo_crop);

        return 0;
    }

    protected function deleteResponsiveVariants(Artist $artist): bool
    {
        return Storage::cloud()->delete(
            collect($this->getResponsiveSizes())
            ->map(fn ($size) => "photos/{$size}/{$artist->photo}")
            ->all()
        );
    }

    protected function getResponsiveSizes(): array
    {
        return ProcessArtistPhoto::$sizes;
    }
}
