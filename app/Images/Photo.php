<?php

namespace App\Images;

use App\Images\Jobs\GenerateArtistPhotoPlaceholders;
use App\Images\Jobs\GenerateArtistPhotoVariants;
use App\Images\Values\ArtistPhotoCrop;
use App\Models\Artist;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Http\File;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Http;
use Spatie\TemporaryDirectory\TemporaryDirectory;

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

    protected static function booted(): void
    {
        self::updated(function (self $photo) {
            if ($photo->isDirty('crop')) {
                $photo->reprocess();
            }
        });
    }

    public static function fromUrl(string $uri, array $attributes = []): self
    {
        $contents = Http::get($uri);

        $temporaryDirectory = (new TemporaryDirectory)->create();

        $targetFile = $temporaryDirectory->path('uploaded-photo.jpeg');

        touch($targetFile);

        $targetStream = fopen($targetFile, 'a');

        fwrite($targetStream, $contents);

        fclose($targetStream);

        $photo = self::store(new File($targetFile), $attributes);

        $temporaryDirectory->delete();

        return $photo;
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
        if ($key !== 'crop') {
            return parent::originalIsEquivalent($key);
        }

        return (string) $this->getOriginal('crop') === (string) $this->getAttribute('crop');
    }
}
