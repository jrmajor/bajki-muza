<?php

namespace App\Console\Commands;

use App\Images\Exceptions\OriginalDoesNotExist;
use App\Images\Photo;
use App\Models\Artist;
use Illuminate\Console\Command;
use Psl\Str;
use Psl\Type;

class ReprocessPhotos extends Command
{
    protected $signature = 'reprocess:photos {--A|artist= : Slug for the artist to process}';

    protected $description = 'Remove responsive variants of artists photos and process them.';

    public function handle(): int
    {
        if ($this->option('artist') !== null) {
            return $this->handleSingleArtist()->value;
        }

        if ($this->confirm('Do you want to reprocess all photos?', true)) {
            return $this->handleAllArtists()->value;
        }

        return 1;
    }

    protected function handleSingleArtist(): ExitCode
    {
        $artist = Artist::firstWhere('slug', $this->option('artist'));

        if (! $artist) {
            $this->error('Artist does not exist.');

            return ExitCode::Error;
        }

        if (! $artist->photo) {
            $this->error('Artist does not have a photo.');

            return ExitCode::Error;
        }

        return $this->reprocessPhoto($artist->photo);
    }

    protected function handleAllArtists(): ExitCode
    {
        $artists = Artist::whereNotNull('photo_filename')->get();

        $total = $artists->count();

        return $artists->map(function ($artist, $index) use ($total) {
            $photo = Type\instance_of(Photo::class)->coerce($artist->photo);

            $this->info(Str\format(
                'Processing artist %d of %d: %s (%s)',
                ++$index, $total, $artist->name, $photo->filename(),
            ));

            return $this->reprocessPhoto($photo);
        })->contains(ExitCode::Error) ? ExitCode::Error : ExitCode::Ok;
    }

    protected function reprocessPhoto(Photo $photo): ExitCode
    {
        if (count($missing = $photo->missingResponsiveVariants()) !== 0) {
            $missing = Type\vec(Type\string())->coerce($missing);
            $missing = Str\join($missing, ', ');

            $this->warn("Some of responsive variants were missing ({$missing}).");
        }

        try {
            $photo->reprocess();

            return ExitCode::Ok;
        } catch (OriginalDoesNotExist $e) {
            $this->error("The original photo doesn't exist ({$e->path}).");

            return ExitCode::Error;
        }
    }
}
