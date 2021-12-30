<?php

namespace App\Console\Commands;

use App\Images\Exceptions\OriginalDoesNotExist;
use App\Images\Photo;
use App\Models\Artist;
use Illuminate\Console\Command;

class ReprocessPhotos extends Command
{
    protected $signature = 'reprocess:photos {--A|artist= : Slug for the artist to process}';

    protected $description = 'Remove responsive variants of artists photos and process them.';

    public function handle(): int
    {
        if ($this->option('artist') !== null) {
            return $this->handleSingleArtist();
        }

        if ($this->confirm('Do you want to reprocess all photos?', true)) {
            return $this->handleAllArtists();
        }

        return 1;
    }

    protected function handleSingleArtist(): int
    {
        $artist = Artist::firstWhere('slug', $this->option('artist'));

        if (! $artist) {
            $this->error('Artist does not exist.');

            return 1;
        }

        if (! $artist->photo) {
            $this->error('Artist does not have a photo.');

            return 1;
        }

        return $this->reprocessPhoto($artist->photo);
    }

    protected function handleAllArtists(): int
    {
        $artists = Artist::whereNotNull('photo_filename')->get();

        $total = $artists->count();

        return $artists
            ->map(function ($artist, $index) use ($total) {
                assert($artist->photo !== null);

                $index++;
                $this->info("Processing artist {$index} of {$total}: {$artist->name} ({$artist->photo->filename()})");

                return $this->reprocessPhoto($artist->photo);
            })
            ->contains(1) ? 1 : 0;
    }

    protected function reprocessPhoto(Photo $photo): int
    {
        if (count($missing = $photo->missingResponsiveVariants()) !== 0) {
            $missing = implode(', ', $missing);

            $this->warn("Some of responsive variants were missing ({$missing}).");
        }

        try {
            $photo->reprocess();

            return 0;
        } catch (OriginalDoesNotExist $exception) {
            $this->error("The original photo doesn't exist ({$exception->path}).");

            return 1;
        }
    }
}
