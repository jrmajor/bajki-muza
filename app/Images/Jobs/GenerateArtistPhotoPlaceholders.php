<?php

namespace App\Images\Jobs;

use App\Images\Photo;
use App\Services\Image;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Spatie\TemporaryDirectory\TemporaryDirectory;

class GenerateArtistPhotoPlaceholders implements ShouldQueue, ShouldBeUnique
{
    use CropsArtistPhoto;
    use ProcessesImages;
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public function __construct(
        protected Photo $image,
    ) { }

    public function uniqueId(): string
    {
        return $this->image->filename();
    }

    public function handle()
    {
        $this->temporaryDirectory = (new TemporaryDirectory())->create();

        $sourceStream = Photo::disk()->readStream(
            $this->image->originalPath(),
        );

        $baseImagePath = $this->copyToTemporaryDirectory(
            $sourceStream, $this->image->filename(),
        );

        $croppedImagePath = $this->cropImage($baseImagePath);

        $croppedFacePath = $this->cropFace($baseImagePath);

        $croppedImage = Image::load($croppedImagePath);

        $this->image->update([
            'width' => $croppedImage->getWidth(),
            'height' => $croppedImage->getHeight(),
            'placeholder' => $this->generateTinyJpg($croppedImagePath, 'height'),
            'face_placeholder' => $this->generateTinyJpg($croppedFacePath, 'square'),
        ]);

        $this->temporaryDirectory->delete()
            ?: throw new Exception('Failed to delete temporary directory.');
    }
}
