<?php

namespace App\Values\Wikipedia;

use Spatie\DataTransferObject\DataTransferObjectCollection;

class ArtistCollection extends DataTransferObjectCollection
{
    public function current(): Artist
    {
        return parent::current();
    }

    public static function fromArray(array $artists): self
    {
        return new self(
            array_map(fn ($artist) => Artist::fromArray($artist), $artists)
        );
    }
}
