<?php

namespace App\Images\Jobs;

use App\Images\Photo;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Spatie\Image\Image;
use Spatie\TemporaryDirectory\TemporaryDirectory;

class GenerateArtistPhotoPlaceholders implements ShouldQueue
{
    use CropsArtistPhoto, ProcessesImages;
    use Dispatchable, InteractsWithQueue, Queueable;

    public function __construct(
        protected Photo $image,
    ) { }

    public function handle()
    {
        $temporaryDirectory = (new TemporaryDirectory)->create();

        $sourceStream = Photo::disk()->readStream(
            $this->image->originalPath(),
        );

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

        $this->image->fill([
            'width' => $croppedImage->getWidth(),
            'height' => $croppedImage->getHeight(),
            'placeholder' => $this->generateTinyJpg(
                $croppedImagePath, 'height', $temporaryDirectory,
            ),
            'face_placeholder' => $this->generateTinyJpg(
                $croppedFacePath, 'square', $temporaryDirectory,
            ),
        ])->save();

        $temporaryDirectory->delete();
    }
}
