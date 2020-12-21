<?php

namespace Tests\Unit\Images;

use App\Images\Image;
use Illuminate\Support\Collection;

class TestCover extends Image
{
    protected $table = 'covers';

    public static function sizes(): Collection
    {
        return collect([128, 192, 256]);
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
