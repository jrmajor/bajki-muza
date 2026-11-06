<?php

namespace App\Images\Values;

final readonly class ArtistImageCrop
{
    public function __construct(
        public int $x,
        public int $y,
        public int $width,
        public int $height,
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
