<?php

namespace Tests\Unit\Images;

use App\Images\Image;

class TestCover extends Image
{
    protected $table = 'covers';

    public static function sizes(): array
    {
        return [128, 192, 256];
    }

    protected function process(): void
    {
        dispatch(new ProcessTestCover());
    }

    protected static function uploadPath(): string
    {
        return 'covers/original';
    }

    public function originalPath(): string
    {
        return "covers/original/{$this->filename()}";
    }

    public function path(int $size): string
    {
        return "covers/{$size}/{$this->filename()}";
    }
}
