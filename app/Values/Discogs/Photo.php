<?php

namespace App\Values\Discogs;

use Illuminate\Contracts\Support\Arrayable;
use Spatie\DataTransferObject\DataTransferObject;

class Photo extends DataTransferObject implements Arrayable
{
    public string $type;

    public string $uri;

    public string $uri150;

    public int $width;

    public int $height;
}
