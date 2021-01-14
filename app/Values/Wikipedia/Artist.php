<?php

namespace App\Values\Wikipedia;

use Spatie\DataTransferObject\DataTransferObject;

class Artist extends DataTransferObject
{
    public string $id;

    public string $name;

    public static function fromArray(array $artist): self
    {
        return new self([
            'id' => $artist['id'],
            'name' => $artist['name'],
        ]);
    }
}
