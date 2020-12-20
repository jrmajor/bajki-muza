<?php

namespace App\Images\Jobs;

use App\Images\Values\ArtistPhotoCrop;
use Spatie\Image\Image;
use Spatie\TemporaryDirectory\TemporaryDirectory;

trait CropsArtistPhoto
{
    abstract public function appendToFileName(string $filePath, string $suffix): string;

    public function cropImage(
        string $baseImagePath,
        ArtistPhotoCrop $crop,
        TemporaryDirectory $temporaryDirectory
    ): string {
        $croppedImageName = $this->appendToFileName($baseImagePath, '_image');

        $croppedImagePath = $temporaryDirectory->path($croppedImageName);

        Image::load($baseImagePath)
            ->manualCrop(
                $crop->image->width,
                $crop->image->height,
                $crop->image->x,
                $crop->image->y,
            )->save($croppedImagePath);

        return $croppedImagePath;
    }

    public function cropFace(
        string $baseImagePath,
        ArtistPhotoCrop $crop,
        TemporaryDirectory $temporaryDirectory
    ): string {
        $croppedFaceName = $this->appendToFileName($baseImagePath, '_face');

        $croppedFacePath = $temporaryDirectory->path($croppedFaceName);

        Image::load($baseImagePath)
            ->manualCrop(
                $crop->face->size,
                $crop->face->size,
                $crop->face->x,
                $crop->face->y,
            )->save($croppedFacePath);

        return $croppedFacePath;
    }
}
