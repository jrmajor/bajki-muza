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
        $artist = Artist::where('slug', $this->option('artist'))->first();

        if (! $artist) {
            $this->error('Artist does not exist.');

            return 1;
        }

        if ($artist->photo === null) {
            $this->error('Artist does not have a photo.');

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
                $this->info("Processing artist {$index} of {$total}: {$artist->name} ({$artist->photo->filename()})");

                return $this->reprocessArtist($artist);
            })
            ->contains(1) ? 1 : 0;
    }

    protected function reprocessArtist(Artist $artist): int
    {
        if (($missing = $artist->photo->missingResponsiveVariants())->isNotEmpty()) {
            $missing = $missing->join(', ');

            $this->warn("Some of responsive variants were missing ({$missing}).");
        }

        try {
            $artist->photo->reprocess(
                function (
                    Photo $photo,
                    int $width,
                    int $height,
                    string $placeholder,
                    string $facePlaceholder,
                ) use ($artist) {
                    $artist->forceFill([
                        'photo_width' => $width,
                        'photo_height' => $height,
                        'photo_face_placeholder' => $facePlaceholder,
                        'photo_placeholder' => $placeholder,
                    ])->save();
                },
            );

            return 0;
        } catch (OriginalDoesNotExist $exception) {
            $this->error("The original photo doesn't exist ({$exception->path}).");

            return 1;
        }
    }
}
