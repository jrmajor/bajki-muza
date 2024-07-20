<?php

namespace App\Images;

use App\Images\Jobs\GenerateImageVariants;
use App\Images\Jobs\GenerateTaleCoverPlaceholder;
use App\Models\Tale;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Bus;
use Intervention\Image\Interfaces\ImageInterface;

final class Cover extends Image
{
    protected function process(): void
    {
        Bus::chain([
            new GenerateTaleCoverPlaceholder($this),
            new GenerateImageVariants($this),
        ])->dispatch();
    }

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

    public function path(string $variant): string
    {
        return "covers/{$variant}/{$this->filename()}";
    }

    public function tales(): HasMany
    {
        return $this->hasMany(Tale::class, 'cover_filename', 'filename');
    }
}
