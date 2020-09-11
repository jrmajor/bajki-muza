<?php

namespace App\Jobs;

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
        TemporaryDirectory $temporaryDirectory
    ): string {
        $responsiveImageName = $this->appendToFileName($baseImagePath, '_tiny');

        $temporaryDestination = $temporaryDirectory->path($responsiveImageName);

        Image::load($baseImagePath)
            ->fit(Manipulations::FIT_CROP, 32, 32)
            ->blur(5)
            ->save($temporaryDestination);

        $tinyImageDataBase64 = base64_encode(file_get_contents($temporaryDestination));

        $tinyImageBase64 = 'data:image/jpeg;base64,'.$tinyImageDataBase64;

        // $originalImage = Image::load($baseImagePath);

        $originalImageWidth = 32;

        $originalImageHeight = 32;

        $svg = view(
            'components.placeholderSvg',
            compact('originalImageWidth', 'originalImageHeight', 'tinyImageBase64')
        );

        return 'data:image/svg+xml;base64,'.base64_encode($svg);
    }

    public function generateResponsiveImage(
        string $baseImagePath,
        int $targetSize,
        string $targetFit,
        TemporaryDirectory $temporaryDirectory
    ): string {
        $responsiveImageName = $this->appendToFileName($baseImagePath, "_$targetSize");

        $responsiveImagePath = $temporaryDirectory->path($responsiveImageName);

        if ($targetFit == 'fit') {
            Image::load($baseImagePath)
                ->optimize()
                ->fit(Manipulations::FIT_CROP, $targetSize, $targetSize)
                ->save($responsiveImagePath);
        } elseif ($target == 'height') {
            Image::load($baseImagePath)
                ->optimize()
                ->height($targetSize)
                ->save($responsiveImagePath);
        }

        return $responsiveImagePath;
    }

    public function appendToFileName(string $filePath, string $suffix): string
    {
        $baseName = pathinfo($filePath, PATHINFO_FILENAME);

        $extension = pathinfo($filePath, PATHINFO_EXTENSION);

        return "{$baseName}{$suffix}.{$extension}";
    }
}
