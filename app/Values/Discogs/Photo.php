<?php

namespace App\Values\Discogs;

use Illuminate\Contracts\Support\Arrayable;
use Spatie\DataTransferObject\DataTransferObject;

class Photo extends DataTransferObject implements Arrayable
{
    public readonly string $type;

    public readonly string $uri;

    public readonly string $uri150;

    public readonly int $width;

    public readonly int $height;
}
