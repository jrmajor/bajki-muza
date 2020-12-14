<?php

namespace App\Jobs;

use App\Models\Tale;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Spatie\TemporaryDirectory\TemporaryDirectory;

class ProcessTaleCover implements ShouldQueue
{
    use ProcessesImages, Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public static $sizes = [
        60, // 3.75rem
        90, // 3.75rem * 1.5
        120, // 3.75rem * 2
        128, // 8rem
        192, // 12rem, 8rem * 1.5
        288, // 12rem * 1.5
        256, // 8rem * 2
        384, // 12rem * 2
    ];

    public function __construct(
        protected Tale $tale,
        protected string $filename,
    ) { }

    public function handle(): void
    {
        $temporaryDirectory = (new TemporaryDirectory)->create();

        $sourceFile = "covers/original/{$this->filename}";

        $sourceStream = Storage::cloud()->readStream($sourceFile);

        $baseImagePath = $this->copyToTemporaryDirectory($sourceStream, $temporaryDirectory, $this->filename);

        $this->tale->forceFill([
            'cover' => $this->filename,
            'cover_placeholder' => $this->generateTinyJpg($baseImagePath, 'square', $temporaryDirectory),
        ])->save();

        foreach (self::$sizes as $size) {
            $responsiveImagePath = $this->generateResponsiveImage($baseImagePath, $size, 'square', $temporaryDirectory);

            $file = fopen($responsiveImagePath, 'r');

            Storage::cloud()
                ->put("covers/{$size}/{$this->filename}", $file, 'public');
        }

        $temporaryDirectory->delete();
    }
}
