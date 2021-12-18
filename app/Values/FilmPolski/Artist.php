<?php

namespace App\Values\FilmPolski;

use Spatie\DataTransferObject\DataTransferObject;

class Artist extends DataTransferObject
{
    public readonly int $id;

    public readonly string $name;
}
