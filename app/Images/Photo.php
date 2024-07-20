<?php

namespace App\Images;

use App\Images\Jobs\GenerateArtistPhotoPlaceholders;
use App\Images\Jobs\GenerateImageVariants;
use App\Images\Values\ArtistPhotoCrop;
use App\Models\Artist;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Bus;
use Intervention\Image\Interfaces\ImageInterface;
use Psl\Type;
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

    public static function variants(): array
    {
        return ['default', 'face'];
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
            new GenerateImageVariants($this),
        ])->dispatch();
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

    protected static function uploadPath(): string
    {
        return 'photos/original';
    }

    public function originalPath(): string
    {
        return "photos/original/{$this->filename()}";
    }

    public function path(int|string $variant): string
    {
        return "photos/{$variant}/{$this->filename()}";
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

        $t = Type\float();

        return $t->coerce($this->width) / $t->coerce($this->height);
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
