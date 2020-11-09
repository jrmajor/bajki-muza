<?php

namespace App\Values;

use Spatie\DataTransferObject\DataTransferObject;

class ArtistFaceCrop extends DataTransferObject
{
    public int $x;
    public int $y;

    public int $size;
}
