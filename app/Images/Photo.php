<?php

namespace App\Images;

use App\Images\Jobs\GenerateArtistPhotoPlaceholders;
use App\Images\Jobs\GenerateArtistPhotoVariants;
use App\Images\Values\ArtistPhotoCrop;
use Closure;
use Illuminate\Queue\SerializableClosure;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Bus;

class Photo extends Image
{
    public function __construct(
        string $filename,
        protected ?ArtistPhotoCrop $crop = null,
    ) {
        parent::__construct($filename);
    }

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
        return self::imageSizes()->concat(self::faceSizes());
    }

    protected function process(Closure $callback): void
    {
        Bus::chain([
            new GenerateArtistPhotoPlaceholders($this, new SerializableClosure($callback)),
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

    public function setCrop(ArtistPhotoCrop $crop): static
    {
        $this->crop = $crop;

        return $this;
    }

    public function crop(): ArtistPhotoCrop
    {
        return $this->crop;
    }
}
