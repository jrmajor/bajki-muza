<?php

namespace App\Images\Values;

use Spatie\DataTransferObject\Attributes\Strict;
use Spatie\DataTransferObject\DataTransferObject;

#[Strict]
final class ArtistImageCrop extends DataTransferObject
{
    public int $x;

    public int $y;

    public int $width;

    public int $height;
}
