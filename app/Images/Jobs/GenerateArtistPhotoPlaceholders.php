<?php

namespace App\Images\Jobs;

use App\Images\Photo;
use App\Images\Values\FitMethod;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class GenerateArtistPhotoPlaceholders implements ShouldQueue, ShouldBeUnique
{
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

    public function handle(): void
    {
        $sourceStream = Photo::disk()->readStream(
            $this->image->originalPath(),
        );

        $imageProcessor = new ImageProcessor($sourceStream);

        fclose($sourceStream);

        $crop = $this->image->crop();
        $croppedFace = $imageProcessor->cropFace($crop->face, $this->image->grayscale);
        $croppedImage = $imageProcessor->cropImage($crop->image, $this->image->grayscale);

        $this->image->update([
            ...$croppedImage->dimensions(),
            'placeholder' => $croppedImage->generateTinyJpg(FitMethod::Height),
            'face_placeholder' => $croppedFace->generateTinyJpg(FitMethod::Square),
        ]);
    }
}
