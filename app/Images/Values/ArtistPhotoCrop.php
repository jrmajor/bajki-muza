<?php

namespace App\Images\Values;

use JessArcher\CastableDataTransferObject\CastableDataTransferObject;
use Spatie\DataTransferObject\Attributes\Strict;
use Stringable;

#[Strict]
final class ArtistPhotoCrop extends CastableDataTransferObject implements Stringable
{
    public ArtistFaceCrop $face;

    public ArtistImageCrop $image;

    public static function fake(): self
    {
        return new self(
            face: ['x' => 181, 'y' => 246, 'size' => 189],
            image: ['x' => 79, 'y' => 247, 'width' => 529, 'height' => 352],
        );
    }

    public function __toString(): string
    {
        return $this->toJson();
    }
}
