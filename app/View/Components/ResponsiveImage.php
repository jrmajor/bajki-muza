<?php

namespace App\View\Components;

use App\Images\Image;
use Illuminate\View\Component;
use InvalidArgumentException;

class ResponsiveImage extends Component
{
    public string $class;

    public int $imageSize;

    public function __construct(
        public Image $image,
        /** @var 'full'|int */
        public string|int $size,
        ?int $imageSize = null,
    ) {
        if ($imageSize === null && is_string($size)) {
            throw new InvalidArgumentException('No $imageSize provided while using string size.');
        }

        $this->imageSize = $imageSize ?? $size * 4;

        $this->class = "size-{$size} object-center object-cover transition-opacity duration-300 opacity-0";
    }

    public function render(): string
    {
        return <<<'blade'
              <img {{ $attributes->merge(['loading' => 'lazy', 'class' => $class]) }}
                src="{{ $image->url($imageSize * 2) }}"
                srcset="
                  {{ $image->url($imageSize) }} 1x,
                  {{ $image->url($imageSize * 1.5) }} 1.5x,
                  {{ $image->url($imageSize * 2) }} 2x">
            blade;
    }
}
