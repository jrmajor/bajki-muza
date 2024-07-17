<?php

namespace App\Images\Jobs;

use App\Images\Values\ArtistFaceCrop;
use App\Images\Values\ArtistImageCrop;
use App\Images\Values\FitMethod;
use Exception;
use Intervention\Image as Intervention;
use Psl\Encoding\Base64;
use Psl\File;
use Psl\Filesystem;
use Psl\Math;
use Psl\Type;

final class ImageProcessor
{
    /** @var non-empty-string */
    private string $path;

    private Intervention\ImageManager $manager;

    /**
     * @param resource $baseImage
     */
    public function __construct(
        $baseImage,
        private string $extension,
    ) {
        $baseImage = Type\resource('stream')->coerce($baseImage);

        $this->path = $this->copyToTemporaryDirectory($baseImage);

        $this->manager = new Intervention\ImageManager(
            new Intervention\Drivers\Gd\Driver(),
        );
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
        $target = File\open_write_only(
            $path = $this->createTemporaryFile(),
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

        return $path;
    }

    /**
     * @return array{width: int, height: int}
     */
    public function dimensions(): array
    {
        $image = $this->manager->read($this->path);

        return ['width' => $image->width(), 'height' => $image->height()];
    }

    /**
     * @return non-empty-string
     */
    public function generateTinyJpg(FitMethod $fit): string
    {
        $image = $this->manager->read($this->path);

        $originalWidth = (int) match ($fit) {
            FitMethod::Square => 32,
            FitMethod::Height => Math\round($image->width() / $image->height() * 32),
        };

        match ($fit) {
            FitMethod::Square => $image->cover(width: 32, height: 32),
            FitMethod::Height => $image->scale(height: 32),
        };

        $file = $this->createTemporaryFile();

        try {
            $image->blur()->save($file);

            $tinyImageDataBase64 = Base64\encode(File\read($file));
        } finally {
            Filesystem\delete_file($file);
        }

        $svg = view('placeholderSvg', [
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
        $image = $this->manager->read($this->path);

        match ($fit) {
            FitMethod::Square => $image->cover(width: $targetSize, height: $targetSize),
            FitMethod::Height => $image->scale(height: $targetSize),
        };

        $image->save($file = $this->createTemporaryFile());

        return $file;
    }

    public function cropImage(ArtistImageCrop $crop, bool $grayscale): self
    {
        return $this->manualCrop(
            $crop->width, $crop->height,
            $crop->x, $crop->y,
            $grayscale,
        );
    }

    public function cropFace(ArtistFaceCrop $crop, bool $grayscale): self
    {
        return $this->manualCrop(
            $crop->size, $crop->size,
            $crop->x, $crop->y,
            $grayscale,
        );
    }

    private function manualCrop(int $width, int $height, int $x, int $y, bool $grayscale): self
    {
        $image = $this->manager->read($this->path);

        $image->crop($width, $height, $x, $y);

        if ($grayscale) {
            $image->greyscale();
        }

        $image->save($path = $this->createTemporaryFile());

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
