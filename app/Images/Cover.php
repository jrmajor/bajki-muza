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
    /**
     * @return list<positive-int>
     */
    public static function sizes(): array
    {
        return [
            60, // 3.75rem
            90, // 3.75rem * 1.5
            120, // 3.75rem * 2
            128, // 8rem
            192, // 12rem, 8rem * 1.5
            288, // 12rem * 1.5
            256, // 8rem * 2
            384, // 12rem * 2
        ];
    }

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

    public function path(int|string $variant): string
    {
        return "covers/{$variant}/{$this->filename()}";
    }

    public function tales(): HasMany
    {
        return $this->hasMany(Tale::class, 'cover_filename', 'filename');
    }
}
