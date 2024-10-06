<?php

namespace App\Images;

use App\Models\Tale;
use Exception;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Intervention\Image\Interfaces\ImageInterface;

final class Cover extends Image
{
    protected $casts = [
        'size' => 'int',
    ];

    public function processVariant(ImageInterface $image, string $variant): ImageInterface
    {
        $size = min($image->width(), $image->height());

        return $image->cover($size, $size);
    }

    public function saveDimensions(int $width, int $height): void
    {
        if ($width !== $height) {
            throw new Exception('Expected tale cover to be square.');
        }

        $this->size = $width;
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
