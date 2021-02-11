<?php

namespace App\Images\Values;

use JessArcher\CastableDataTransferObject\CastableDataTransferObject;
use Stringable;

class ArtistPhotoCrop extends CastableDataTransferObject implements Stringable
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

    public function __toString(): string
    {
        return $this->toJson();
    }

    public static function fake(): self
    {
        return new self([
            'face' => [
                'x' => 181,
                'y' => 246,
                'size' => 189,
            ],
            'image' => [
                'x' => 79,
                'y' => 247,
                'width' => 529,
                'height' => 352,
            ],
        ]);
    }
}
