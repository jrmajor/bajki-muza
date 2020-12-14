<?php

namespace App\Jobs;

use InvalidArgumentException;
use Spatie\Image\Image;
use Spatie\Image\Manipulations;
use Spatie\TemporaryDirectory\TemporaryDirectory;

trait ProcessesImages
{
    public function copyToTemporaryDirectory(
        $sourceStream,
        TemporaryDirectory $temporaryDirectory,
        string $filename
    ): string {
        $targetFile = $temporaryDirectory->path($filename);

        touch($targetFile);

        $targetStream = fopen($targetFile, 'a');

        while (! feof($sourceStream)) {
            $chunk = fgets($sourceStream, 1024);
            fwrite($targetStream, $chunk);
        }

        fclose($sourceStream);

        fclose($targetStream);

        return $targetFile;
    }

    public function generateTinyJpg(
        string $baseImagePath,
        string $fit,
        TemporaryDirectory $temporaryDirectory
    ): string {
        $responsiveImageName = $this->appendToFileName($baseImagePath, '_tiny');

        $temporaryDestination = $temporaryDirectory->path($responsiveImageName);

        $image = Image::load($baseImagePath);

        if ($fit === 'square') {
            $originalImageWidth = $originalImageHeight = 32;

            $image->fit(Manipulations::FIT_CROP, 32, 32);
        } elseif ($fit === 'height') {
            $originalImageWidth = (int) round($image->getWidth() / $image->getHeight() * 32);

            $originalImageHeight = 32;

            $image->height(32);
        } else {
            throw new InvalidArgumentException();
        }

        $image
            ->blur(5)
            ->save($temporaryDestination);

        $tinyImageDataBase64 = base64_encode(file_get_contents($temporaryDestination));

        $tinyImageBase64 = 'data:image/jpeg;base64,'.$tinyImageDataBase64;

        $svg = view(
            'components.placeholderSvg',
            compact('originalImageWidth', 'originalImageHeight', 'tinyImageBase64')
        );

        return 'data:image/svg+xml;base64,'.base64_encode($svg);
    }

    public function generateResponsiveImage(
        string $baseImagePath,
        int $targetSize,
        string $fit,
        TemporaryDirectory $temporaryDirectory
    ): string {
        $responsiveImageName = $this->appendToFileName($baseImagePath, "_$targetSize");

        $responsiveImagePath = $temporaryDirectory->path($responsiveImageName);

        $image = Image::load($baseImagePath)
                    ->optimize();

        if ($fit === 'square') {
            $image->fit(Manipulations::FIT_CROP, $targetSize, $targetSize);
        } elseif ($fit === 'height') {
            $image->height($targetSize);
        } else {
            throw new InvalidArgumentException();
        }

        $image->save($responsiveImagePath);

        return $responsiveImagePath;
    }

    public function appendToFileName(string $filePath, string $suffix): string
    {
        $baseName = pathinfo($filePath, PATHINFO_FILENAME);

        $extension = pathinfo($filePath, PATHINFO_EXTENSION);

        return "{$baseName}{$suffix}.{$extension}";
    }
}
