<?php

namespace App\Images\Jobs;

use App\Images\Cover;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Spatie\TemporaryDirectory\TemporaryDirectory;

class GenerateTaleCoverPlaceholder implements ShouldQueue, ShouldBeUnique
{
    use ProcessesImages;

    use Dispatchable,
        InteractsWithQueue,
        Queueable,
        SerializesModels;

    public function __construct(
        protected Cover $image,
    ) { }

    public function uniqueId(): string
    {
        return $this->image->filename;
    }

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
