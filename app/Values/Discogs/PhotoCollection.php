<?php

namespace App\Values\Discogs;

use Spatie\DataTransferObject\DataTransferObjectCollection;

class PhotoCollection extends DataTransferObjectCollection
{
    public function current(): Photo
    {
        return parent::current();
    }

    public static function fromArray(array $photos): self
    {
        return new self(
            array_map(fn ($photo) => new Photo($photo), $photos)
        );
    }

    public function primary(): ?Photo
    {
        return $this->collection[0] ?? null;
    }
}
