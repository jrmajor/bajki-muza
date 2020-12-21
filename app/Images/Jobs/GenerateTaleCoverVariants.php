<?php

namespace App\Images\Jobs;

use App\Images\Cover;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Spatie\TemporaryDirectory\TemporaryDirectory;

class GenerateTaleCoverVariants implements ShouldQueue
{
    use ProcessesImages, Dispatchable, InteractsWithQueue, Queueable;

    public function __construct(
        protected Cover $image,
    ) { }

    public function handle()
    {
        $temporaryDirectory = (new TemporaryDirectory)->create();

        $sourceStream = Cover::disk()->readStream(
             $this->image->originalPath(),
        );

        $baseImagePath = $this->copyToTemporaryDirectory(
            $sourceStream,
            $temporaryDirectory,
            $this->image->filename(),
        );

        foreach (Cover::sizes() as $size) {
            $responsiveImagePath = $this->generateResponsiveImage(
                $baseImagePath,
                $size, 'square',
                $temporaryDirectory,
            );

            $file = fopen($responsiveImagePath, 'r');

            Cover::disk()
                ->put("covers/{$size}/{$this->image->filename()}", $file, 'public');
        }

        $temporaryDirectory->delete();
    }
}
