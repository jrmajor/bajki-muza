<?php

namespace App\Values\Wikipedia;

use Spatie\DataTransferObject\DataTransferObject;

class Artist extends DataTransferObject
{
    public readonly string $id;

    public readonly string $name;
}
