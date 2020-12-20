<?php

namespace App\Images\Jobs;

use App\Images\Photo;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializableClosure;
use Illuminate\Support\Facades\Storage;
use Spatie\Image\Image;
use Spatie\TemporaryDirectory\TemporaryDirectory;

class GenerateArtistPhotoPlaceholders implements ShouldQueue
{
    use CropsArtistPhoto, ProcessesImages;
    use Dispatchable, InteractsWithQueue, Queueable;

    public function __construct(
        protected Photo $image,
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

        $croppedImagePath = $this->cropImage(
            $baseImagePath,
            $this->image->crop(),
            $temporaryDirectory,
        );

        $croppedFacePath = $this->cropFace(
            $baseImagePath,
            $this->image->crop(),
            $temporaryDirectory,
        );

        $croppedImage = Image::load($croppedImagePath);

        $data = [
            'photo' => $this->image,
            'width' => $croppedImage->getWidth(),
            'height' => $croppedImage->getHeight(),
            'placeholder' => $this->generateTinyJpg($croppedImagePath, 'height', $temporaryDirectory),
            'facePlaceholder' => $this->generateTinyJpg($croppedFacePath, 'square', $temporaryDirectory),
        ];

        $temporaryDirectory->delete();

        ($this->callback)(...array_values($data));
    }
}
