<?php

namespace App\Jobs;

use App\Models\Artist;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Spatie\Image\Image;
use Spatie\TemporaryDirectory\TemporaryDirectory;

class ProcessArtistPhoto implements ShouldQueue
{
    use ProcessesImages, Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $artist;

    protected $filename;

    public static $sizes = [
        56, // 3.5rem
        84, // 3.5rem * 1.5
        112, // 3.5rem * 2
        160, // 10rem
        240, // 10rem * 1.5
        320, // 10rem * 2
    ];

    public function __construct(Artist $artist, $filename)
    {
        $this->artist = $artist;

        $this->filename = $filename;
    }

    public function handle(): void
    {
        $temporaryDirectory = (new TemporaryDirectory)->create();

        $sourceFile = "photos/original/{$this->filename}";

        $sourceStream = Storage::cloud()->readStream($sourceFile);

        $baseImagePath = $this->copyToTemporaryDirectory($sourceStream, $temporaryDirectory, $this->filename);

        $image = Image::load($baseImagePath);

        $this->artist->forceFill([
            'photo' => $this->filename,
            'photo_width' => $image->getWidth(),
            'photo_height' => $image->getHeight(),
            'photo_placeholder' => $this->generateTinyJpg($baseImagePath, 'height', $temporaryDirectory),
        ])->save();

        foreach (self::$sizes as $size) {
            $responsiveImagePath = $this->generateResponsiveImage($baseImagePath, $size, 'height', $temporaryDirectory);

            $file = fopen($responsiveImagePath, 'r');

            Storage::cloud()
                ->put("photos/{$size}/{$this->filename}", $file, 'public');
        }

        $temporaryDirectory->delete();
    }
}
