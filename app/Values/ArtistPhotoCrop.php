<?php

namespace App\Values;

use JessArcher\CastableDataTransferObject\CastableDataTransferObject;

class ArtistPhotoCrop extends CastableDataTransferObject
{
    public ArtistFaceCrop $face;

    public ArtistImageCrop $image;

    public static function fromStrings(array $crop): self
    {
        return new self([
            'face' => [
                'x' => (int) $crop['face']['x'],
                'y' => (int) $crop['face']['y'],
                'size' => (int) $crop['face']['size'],
            ],
            'image' => [
                'x' => (int) $crop['image']['x'],
                'y' => (int) $crop['image']['y'],
                'width' => (int) $crop['image']['width'],
                'height' => (int) $crop['image']['height'],
            ],
        ]);
    }
}
