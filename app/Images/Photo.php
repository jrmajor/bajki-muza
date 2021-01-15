<?php

namespace App\Images;

use App\Images\Jobs\GenerateArtistPhotoPlaceholders;
use App\Images\Jobs\GenerateArtistPhotoVariants;
use App\Images\Values\ArtistPhotoCrop;
use App\Models\Artist;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Bus;

final class Photo extends Image
{
    protected $casts = [
        'width' => 'int',
        'height' => 'int',
        'crop' => ArtistPhotoCrop::class,
    ];

    public static function imageSizes(): Collection
    {
        return collect([
            160, // 10rem
            240, // 10rem * 1.5
            320, // 10rem * 2
        ]);
    }

    public static function faceSizes(): Collection
    {
        return collect([
            56, // 3.5rem
            84, // 3.5rem * 1.5
            112, // 3.5rem * 2
        ]);
    }

    public static function sizes(): Collection
    {
        return self::imageSizes()
            ->concat(self::faceSizes())
            ->sort()
            ->values();
    }

    protected function process(): void
    {
        Bus::chain([
            new GenerateArtistPhotoPlaceholders($this),
            new GenerateArtistPhotoVariants($this),
        ])->dispatch();
    }

    protected static function uploadPath(): string
    {
        return 'photos/original';
    }

    public function originalPath(): string
    {
        return "photos/original/{$this->filename()}";
    }

    public function path(int $size): string
    {
        return "photos/{$size}/{$this->filename()}";
    }

    public function facePlaceholder(): ?string
    {
        return $this->face_placeholder;
    }

    public function setCrop(ArtistPhotoCrop $crop): self
    {
        return tap(
            $this->setAttribute('crop', $crop)
        )->save();
    }

    public function crop(): ArtistPhotoCrop
    {
        return $this->crop;
    }

    public function aspectRatio(): ?float
    {
        if (! $this->width || ! $this->height) {
            return null;
        }

        return $this->width / $this->height;
    }

    public function artists(): HasMany
    {
        return $this->hasMany(Artist::class, 'photo_filename', 'filename');
    }
}
