<?php

namespace App\Values\Wikipedia;

use Spatie\DataTransferObject\DataTransferObject;

class Artist extends DataTransferObject
{
    public string $id;

    public string $name;
}
