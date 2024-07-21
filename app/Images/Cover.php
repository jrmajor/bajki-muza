<?php

namespace App\Images;

use App\Models\Tale;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Intervention\Image\Interfaces\ImageInterface;

final class Cover extends Image
{
    public function processVariant(ImageInterface $image, string $variant): ImageInterface
    {
        $size = min($image->width(), $image->height());

        return $image->cover($size, $size);
    }

    protected static function pathPrefix(): string
    {
        return 'covers';
    }

    public function tales(): HasMany
    {
        return $this->hasMany(Tale::class, 'cover_filename', 'filename');
    }
}
