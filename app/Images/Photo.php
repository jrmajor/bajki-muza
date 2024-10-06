<?php

namespace App\Images;

use App\Images\Values\ArtistPhotoCrop;
use App\Models\Artist;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Intervention\Image\Interfaces\ImageInterface;

final class Photo extends Image
{
    protected $casts = [
        'width' => 'int',
        'height' => 'int',
        'crop' => ArtistPhotoCrop::class,
        'grayscale' => 'bool',
    ];

    public static function variants(): array
    {
        return ['default', 'face'];
    }

    protected static function booted(): void
    {
        self::updated(function (self $photo) {
            if ($photo->isDirty('crop', 'grayscale')) {
                $photo->reprocess();
            }
        });
    }

    public function processVariant(ImageInterface $image, string $variant): ImageInterface
    {
        if ($variant === 'face') {
            $crop = $this->crop->face;
            $image->crop($crop->size, $crop->size, $crop->x, $crop->y);
        } else {
            $crop = $this->crop->image;
            $image->crop($crop->width, $crop->height, $crop->x, $crop->y);
        }

        return $this->grayscale ? $image->greyscale() : $image;
    }

    public function saveDimensions(int $width, int $height): void
    {
        $this->width = $width;
        $this->height = $height;
    }

    protected static function pathPrefix(): string
    {
        return 'photos';
    }

    public function crop(): ArtistPhotoCrop
    {
        return $this->crop;
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
