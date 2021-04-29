<?php

namespace App\Values\Discogs;

use Spatie\DataTransferObject\DataTransferObject;

class Artist extends DataTransferObject
{
    public int $id;

    public string $name;

    public static function fromArray(array $artist): self
    {
        return new self(
            id: $artist['id'],
            name: $artist['title'],
        );
    }
}
