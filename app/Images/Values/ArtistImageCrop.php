<?php

namespace App\Images\Values;

use Spatie\DataTransferObject\DataTransferObject;

class ArtistImageCrop extends DataTransferObject
{
    public int $x;

    public int $y;

    public int $width;

    public int $height;
}
