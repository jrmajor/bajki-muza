<?php

namespace App\View\Components;

use App\Images\Image;
use Illuminate\View\Component;

class ResponsiveImage extends Component
{
    public string $class;

    public int $imageSize;

    public function __construct(
        public Image $image,
        public string|int $size,
        ?int $imageSize = null,
    ) {
        $this->imageSize = $imageSize ?? $size * 4;

        $this->class = "w-{$size} h-{$size} object-center object-cover transition-opacity duration-300 opacity-0";
    }

    public function render()
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
