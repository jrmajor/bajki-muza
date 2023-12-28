<?php

namespace App\Images\Jobs;

use App\Images\Values\ArtistFaceCrop;
use App\Images\Values\ArtistImageCrop;
use App\Images\Values\FitMethod;
use App\Services\Image;
use Exception;
use Psl\Encoding\Base64;
use Psl\File;
use Psl\Filesystem;
use Psl\Math;
use Psl\Type;
use Spatie\Image\Enums\Fit;

final class ImageProcessor
{
    /** @var non-empty-string */
    private string $path;

    /**
     * @param resource $imageStream
     */
    public function __construct(
        $imageStream,
        private string $extension,
    ) {
        $imageStream = Type\resource('stream')->coerce($imageStream);

        $this->path = $this->copyToTemporaryDirectory($imageStream);
    }

    /**
     * @param non-empty-string $imagePath
     */
    public static function fromPath(string $imagePath): self
    {
        if (! $stream = fopen($imagePath, 'r')) {
            throw new Exception("Failed to fopen the image at {$imagePath}.");
        }

        if (! $extension = Filesystem\get_extension($imagePath)) {
            throw new Exception("Failed to get file extension from path {$imagePath}.");
        }

        $instance = new self($stream, $extension);

        fclose($stream);

        return $instance;
    }

    /**
     * @param resource $baseImage
     *
     * @return non-empty-string
     */
    private function copyToTemporaryDirectory($baseImage): string
    {
        // $baseImage = Type\resource('stream')->coerce($baseImage);

        $target = File\open_write_only(
            $path = Filesystem\create_temporary_file(),
            File\WriteMode::APPEND,
        );

        while (! feof($baseImage)) {
            $chunk = fgets($baseImage, 1024);

            if ($chunk === false) {
                throw new Exception();
            }

            $target->writeAll($chunk);
        }

        $target->close();

        clearstatcache();

        return $path;
    }

    /**
     * @return array{width: int, height: int}
     */
    public function dimensions(): array
    {
        $image = Image::useImageDriver('gd')->loadFile($this->path);

        return ['width' => $image->getWidth(), 'height' => $image->getHeight()];
    }

    /**
     * @return non-empty-string
     */
    public function generateTinyJpg(FitMethod $fit): string
    {
        $image = Image::useImageDriver('gd')->loadFile($this->path);

        $originalWidth = (int) match ($fit) {
            FitMethod::Square => 32,
            FitMethod::Height => Math\round($image->getWidth() / $image->getHeight() * 32),
        };

        match ($fit) {
            FitMethod::Square => $image->fit(Fit::Crop, 32, 32),
            FitMethod::Height => $image->height(32),
        };

        $file = $this->createTemporaryFile();

        try {
            $image->blur(5)->save($file);

            $tinyImageDataBase64 = Base64\encode(File\read($file));
        } finally {
            Filesystem\delete_file($file);
        }

        $svg = view('components.placeholderSvg', [
            'originalWidth' => $originalWidth,
            'originalHeight' => 32,
            'tinyImageBase64' => 'data:image/jpeg;base64,' . $tinyImageDataBase64,
        ]);

        return 'data:image/svg+xml;base64,' . Base64\encode($svg);
    }

    /**
     * @param int<0, max> $targetSize
     *
     * @return non-empty-string
     */
    public function responsiveImage(int $targetSize, FitMethod $fit): string
    {
        $image = Image::useImageDriver('gd')->loadFile($this->path)->optimize();

        match ($fit) {
            FitMethod::Square => $image->fit(Fit::Crop, $targetSize, $targetSize),
            FitMethod::Height => $image->height($targetSize),
        };

        $image->save($file = $this->createTemporaryFile());

        return $file;
    }

    public function cropImage(ArtistImageCrop $crop, bool $grayscale): self
    {
        Image::useImageDriver('gd')->loadFile($this->path)
            ->manualCrop(...$crop->toArray())
            ->when($grayscale, fn (Image $i) => $i->greyscale())
            ->save($path = $this->createTemporaryFile());

        return self::fromPath($path);
    }

    public function cropFace(ArtistFaceCrop $crop, bool $grayscale): self
    {
        Image::useImageDriver('gd')->loadFile($this->path)
            ->manualCrop($crop->size, $crop->size, $crop->x, $crop->y)
            ->when($grayscale, fn (Image $i) => $i->greyscale())
            ->save($path = $this->createTemporaryFile());

        return self::fromPath($path);
    }

    /**
     * @return non-empty-string
     */
    private function createTemporaryFile(): string
    {
        return Filesystem\create_temporary_file() . '.' . $this->extension;
    }

    public function __destruct()
    {
        Filesystem\delete_file($this->path);
    }
}
