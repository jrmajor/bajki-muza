<?php

namespace App\Values\Discogs;

use Spatie\DataTransferObject\FlexibleDataTransferObject;

class Photo extends FlexibleDataTransferObject
{
    public string $type;

    public string $uri;

    public string $uri150;

    public int $width;

    public int $height;
}
