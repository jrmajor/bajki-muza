<?php

namespace App\Values\FilmPolski;

use Spatie\DataTransferObject\DataTransferObject;

class Artist extends DataTransferObject
{
    public int $id;

    public string $name;
}
