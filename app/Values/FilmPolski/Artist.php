<?php

namespace App\Values\FilmPolski;

use Spatie\DataTransferObject\DataTransferObject;

class Artist extends DataTransferObject
{
    public int $id;

    public string $name;

    public static function fromArray(array $artist): self
    {
        return new self([
            'id' => (int) $artist['id'],
            'name' => $artist['name'],
        ]);
    }
}
