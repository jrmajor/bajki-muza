<?php

namespace App\Images;

use App\Images\Jobs\GenerateArtistPhotoPlaceholders;
use App\Images\Jobs\GenerateArtistPhotoVariants;
use App\Images\Values\ArtistPhotoCrop;
use App\Models\Artist;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Bus;
use Psl\Vec;

final class Photo extends Image
{
    protected $casts = [
        'width' => 'int',
        'height' => 'int',
        'crop' => ArtistPhotoCrop::class,
        'grayscale' => 'bool',
    ];

    /**
     * @return list<positive-int>
     */
    public static function imageSizes(): array
    {
        return [
            160, // 10rem
            240, // 10rem * 1.5
            320, // 10rem * 2
        ];
    }

    /**
     * @return list<positive-int>
     */
    public static function faceSizes(): array
    {
        return [
            56, // 3.5rem
            84, // 3.5rem * 1.5
            112, // 3.5rem * 2
        ];
    }

    public static function sizes(): array
    {
        return Vec\sort([...self::faceSizes(), ...self::imageSizes()]);
    }

    protected static function booted(): void
    {
        self::updated(function (self $photo) {
            if ($photo->isDirty('crop', 'grayscale')) {
                $photo->reprocess();
            }
        });
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

    public function originalIsEquivalent($key): bool
    {
        return match ($key) {
            'crop' => $this->getOriginal('crop')?->toArray() === $this->getAttribute('crop')->toArray(),
            default => parent::originalIsEquivalent($key),
        };
    }
}
