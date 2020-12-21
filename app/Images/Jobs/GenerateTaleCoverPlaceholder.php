<?php

namespace App\Images\Jobs;

use App\Images\Cover;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Spatie\TemporaryDirectory\TemporaryDirectory;

class GenerateTaleCoverPlaceholder implements ShouldQueue
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

        $placeholder = $this->generateTinyJpg($baseImagePath, 'square', $temporaryDirectory);

        $this->image->fill([
            'placeholder' => $placeholder,
        ])->save();

        $temporaryDirectory->delete();
    }
}
