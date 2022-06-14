<?php

namespace App\Images\Values;

final class ArtistImageCrop
{
    public function __construct(
        public readonly int $x,
        public readonly int $y,
        public readonly int $width,
        public readonly int $height,
    ) { }

    /**
     * @return array<string, int>
     */
    public function toArray(): array
    {
        return [
            'x' => $this->x,
            'y' => $this->y,
            'width' => $this->width,
            'height' => $this->height,
        ];
    }
}
