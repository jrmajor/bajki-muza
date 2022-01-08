<?php

namespace App\Images\Jobs;

use App;
use App\Images\Values\FitMethod;
use App\Services\Image;
use Exception;
use InvalidArgumentException;
use Psl\Encoding\Base64;
use Psl\File;
use Psl\Math;
use Safe;
use Spatie\Image\Manipulations;
use Spatie\TemporaryDirectory\TemporaryDirectory;

trait ProcessesImages
{
    protected TemporaryDirectory $temporaryDirectory;

    /**
     * @param resource $sourceStream
     * @param non-empty-string $filename
     * @return non-empty-string
     */
    public function copyToTemporaryDirectory($sourceStream, string $filename): string
    {
        $targetPath = $this->temporaryDirectory->path($filename);

        $targetHandle = File\open_write_only($targetPath, File\WriteMode::MUST_CREATE);

        while (! feof($sourceStream)) {
            $chunk = fgets($sourceStream, 1024);

            if ($chunk === false) {
                throw new Exception();
            }

            $targetHandle->writeAll($chunk);
        }

        Safe\fclose($sourceStream);
        $targetHandle->close();

        return $targetPath;
    }

    /**
     * @param non-empty-string $baseImagePath
     * @return non-empty-string
     */
    public function generateTinyJpg(string $baseImagePath, FitMethod $fit): string
    {
        $responsiveImageName = App\append_to_file_name($baseImagePath, 'tiny');

        $temporaryDestination = $this->temporaryDirectory->path($responsiveImageName);

        $image = Image::load($baseImagePath);

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

        $image->blur(5)->save($temporaryDestination);

        $tinyImageDataBase64 = Base64\encode(File\read($temporaryDestination));

        $tinyImageBase64 = 'data:image/jpeg;base64,' . $tinyImageDataBase64;

        $svg = view(
            'components.placeholderSvg',
            compact('originalImageWidth', 'originalImageHeight', 'tinyImageBase64'),
        );

        return 'data:image/svg+xml;base64,' . Base64\encode($svg);
    }

    /**
     * @param non-empty-string $baseImagePath
     * @param int<0, max> $targetSize
     * @return non-empty-string
     */
    public function generateResponsiveImage(
        string $baseImagePath,
        int $targetSize,
        FitMethod $fit,
    ): string {
        $responsiveImageName = App\append_to_file_name($baseImagePath, (string) $targetSize);

        $responsiveImagePath = $this->temporaryDirectory->path($responsiveImageName);

        $image = Image::load($baseImagePath)->optimize();

        match ($fit) {
            FitMethod::Square => $image->fit(Manipulations::FIT_CROP, $targetSize, $targetSize),
            FitMethod::Height => $image->height($targetSize),
        };

        $image->save($responsiveImagePath);

        return $responsiveImagePath;
    }
}
