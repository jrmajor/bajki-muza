<?php

namespace App\Images\Jobs;

use App;
use App\Services\Image;

trait CropsArtistPhoto
{
    /**
     * @param non-empty-string $baseImagePath
     * @return non-empty-string
     */
    public function cropImage(string $baseImagePath): string
    {
        $path = $this->temporaryDirectory->path(
            App\append_to_file_name($baseImagePath, 'image'),
        );

        Image::load($baseImagePath)
            ->manualCrop(...$this->image->crop()->image->toArray())
            ->when($this->image->grayscale, fn (Image $i) => $i->greyscale())
            ->save($path);

        return $path;
    }

    /**
     * @param non-empty-string $baseImagePath
     * @return non-empty-string
     */
    public function cropFace(string $baseImagePath): string
    {
        $crop = $this->image->crop()->face;

        $path = $this->temporaryDirectory->path(
            App\append_to_file_name($baseImagePath, 'face'),
        );

        Image::load($baseImagePath)
            ->manualCrop($crop->size, $crop->size, $crop->x, $crop->y)
            ->when($this->image->grayscale, fn (Image $i) => $i->greyscale())
            ->save($path);

        return $path;
    }
}
