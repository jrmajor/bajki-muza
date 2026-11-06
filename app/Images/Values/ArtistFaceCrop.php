<?php

namespace App\Images\Values;

use Illuminate\Contracts\Support\Arrayable;

/**
 * @implements Arrayable<string, mixed>
 */
final readonly class ArtistFaceCrop implements Arrayable
{
    public function __construct(
        public int $x,
        public int $y,
        public int $size,
    ) { }

    /**
     * @return array<string, int>
     */
    public function toArray(): array
    {
        return [
            'x' => $this->x,
            'y' => $this->y,
            'size' => $this->size,
        ];
    }
}
