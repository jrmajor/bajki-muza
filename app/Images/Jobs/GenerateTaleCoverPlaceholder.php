<?php

namespace App\Images\Jobs;

use App\Images\Cover;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializableClosure;
use Illuminate\Support\Facades\Storage;
use Spatie\TemporaryDirectory\TemporaryDirectory;

class GenerateTaleCoverPlaceholder implements ShouldQueue
{
    use ProcessesImages, Dispatchable, InteractsWithQueue, Queueable;

    public function __construct(
        protected Cover $image,
        protected SerializableClosure $callback,
    ) { }

    public function handle()
    {
        $temporaryDirectory = (new TemporaryDirectory)->create();

        $sourceFile = $this->image->originalPath();

        $sourceStream = Storage::cloud()->readStream($sourceFile);

        $baseImagePath = $this->copyToTemporaryDirectory(
            $sourceStream,
            $temporaryDirectory,
            $this->image->filename(),
        );

        $placeholder = $this->generateTinyJpg($baseImagePath, 'square', $temporaryDirectory);

        $temporaryDirectory->delete();

        ($this->callback)($this->image, $placeholder);
    }
}
