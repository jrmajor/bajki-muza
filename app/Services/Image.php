<?php

namespace App\Services;

use Closure;
use Spatie\Image\Image as SpatieImage;

class Image extends SpatieImage
{
    public function when($value, Closure $callback)
    {
        if ($value) {
            return $callback($this, $value) ?: $this;
        }

        return $this;
    }
}
