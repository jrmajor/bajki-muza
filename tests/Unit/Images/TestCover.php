<?php

namespace Tests\Unit\Images;

use App\Images\Image;
use Intervention\Image\Interfaces\ImageInterface;

class TestCover extends Image
{
    protected $table = 'covers';

    public function processVariant(ImageInterface $image, string $variant): ImageInterface
    {
        $size = min($image->width(), $image->height());

        return $image->cover($size, $size);
    }

    protected static function uploadPath(): string
    {
        return 'covers/original';
    }

    public function originalPath(): string
    {
        return "covers/original/{$this->filename()}";
    }

    public function path(int|string $variant): string
    {
        return "covers/{$variant}/{$this->filename()}";
    }
}
