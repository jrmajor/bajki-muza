<?php

namespace App\Images\Values;

use Illuminate\Contracts\Support\Arrayable;

final class ArtistFaceCrop implements Arrayable
{
    public function __construct(
        public readonly int $x,
        public readonly int $y,
        public readonly int $size,
    ) { }

    public function toArray(): array
    {
        return [
            'x' => $this->x,
            'y' => $this->y,
            'size' => $this->size,
        ];
    }
}
