<?php

namespace App\Images\Jobs;

use App\Images\Values\ArtistFaceCrop;
use App\Images\Values\ArtistImageCrop;
use App\Images\Values\FitMethod;
use App\Services\Image;
use Exception;
use InvalidArgumentException;
use Psl\Encoding\Base64;
use Psl\File;
use Psl\Filesystem;
use Psl\Math;
use Psl\Type;
use Spatie\Image\Manipulations;

final class ImageProcessor
{
    /** @var non-empty-string */
    private string $path;

    /**
     * @param resource|non-empty-string $baseImage
     */
    public function __construct($baseImage) {
//        Type\union(
//            Type\resource('test'),
//            Type\non_empty_string(),
//        )->assert($baseImage);

        if (! is_string($baseImage)) {
            $baseImage = $this->copyToTemporaryDirectory($baseImage);
        }

        $this->path = $baseImage;
    }

    /**
     * @param resource $baseImage
     * @return non-empty-string
     */
    private function copyToTemporaryDirectory($baseImage): string
    {
        $target = File\temporary();

        while (! feof($baseImage)) {
            $chunk = fgets($baseImage, 1024);

            if ($chunk === false) {
                throw new Exception();
            }

            $target->writeAll($chunk);
        }

        $target->close();

        return $target->getPath();
    }

    /**
     * @return array{width: int, height: int}
     */
    public function dimensions(): array
    {
        $image = Image::load($this->path);

        return ['width' => $image->getWidth(), 'height' => $image->getHeight()];
    }

    /**
     * @return non-empty-string
     */
    public function generateTinyJpg(FitMethod $fit): string
    {
        $image = Image::load($this->path);

        if ($fit === FitMethod::Square) {
            $originalImageWidth = $originalImageHeight = 32;

            $image->fit(Manipulations::FIT_CROP, 32, 32);
        } elseif ($fit === FitMethod::Height) {
            $originalImageWidth = (int) Math\round($image->getWidth() / $image->getHeight() * 32);

            $originalImageHeight = 32;

            $image->height(32);
        } else {
            throw new InvalidArgumentException();
        }

        $file = Filesystem\create_temporary_file();

        try {
            $image->blur(5)->save($file);

            $tinyImageDataBase64 = Base64\encode(File\read($file));
        } finally {
            Filesystem\delete_file($file);
        }

        $tinyImageBase64 = 'data:image/jpeg;base64,' . $tinyImageDataBase64;

        $svg = view(
            'components.placeholderSvg',
            compact('originalImageWidth', 'originalImageHeight', 'tinyImageBase64'),
        );

        return 'data:image/svg+xml;base64,' . Base64\encode($svg);
    }

    /**
     * @param int<0, max> $targetSize
     * @return non-empty-string
     */
    public function responsiveImage(
        int $targetSize,
        FitMethod $fit,
    ): string {
        $image = Image::load($this->path)->optimize();

        match ($fit) {
            FitMethod::Square => $image->fit(Manipulations::FIT_CROP, $targetSize, $targetSize),
            FitMethod::Height => $image->height($targetSize),
        };

        $image->save($file = Filesystem\create_temporary_file());

        return $file;
    }

    public function cropImage(ArtistImageCrop $crop, bool $grayscale): self
    {
        Image::load($this->path)
            ->manualCrop(...$crop->toArray())
            ->when($grayscale, fn (Image $i) => $i->greyscale())
            ->save($path = Filesystem\create_temporary_file());

        return new self($path);
    }

    public function cropFace(ArtistFaceCrop $crop, bool $grayscale): self
    {
        Image::load($this->path)
            ->manualCrop($crop->size, $crop->size, $crop->x, $crop->y)
            ->when($grayscale, fn (Image $i) => $i->greyscale())
            ->save($path = Filesystem\create_temporary_file());

        return new self($path);
    }
}
