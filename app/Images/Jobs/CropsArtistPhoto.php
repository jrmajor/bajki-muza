<?php

namespace App\Images\Jobs;

use App\Images\Values\ArtistPhotoCrop;
use Spatie\Image\Image;

trait CropsArtistPhoto
{
    abstract public function appendToFileName(string $filePath, string $suffix): string;

    public function cropImage(string $baseImagePath, ArtistPhotoCrop $crop): string
    {
        $croppedImageName = $this->appendToFileName($baseImagePath, '_image');

        $croppedImagePath = $this->temporaryDirectory->path($croppedImageName);

        Image::load($baseImagePath)
            ->manualCrop(...$crop->image->toArray())
            ->save($croppedImagePath);

        return $croppedImagePath;
    }

    public function cropFace(string $baseImagePath, ArtistPhotoCrop $crop): string
    {
        $croppedFaceName = $this->appendToFileName($baseImagePath, '_face');

        $croppedFacePath = $this->temporaryDirectory->path($croppedFaceName);

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
