<?php

namespace App\Services;

use Illuminate\Support\Traits\Conditionable;
use Spatie\Image\Image as SpatieImage;

class Image extends SpatieImage
{
    use Conditionable;
}
