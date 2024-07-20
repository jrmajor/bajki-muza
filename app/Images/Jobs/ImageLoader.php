<?php

namespace App\Images\Jobs;

use App\Images\Image;
use Exception;
use Intervention\Image as Intervention;
use Intervention\Image\Interfaces\ImageInterface;
use Psl\File;
use Psl\Filesystem;
use Psl\Type;

final class ImageLoader
{
    private Intervention\ImageManager $manager;

    public function __construct()
    {
        $this->manager = new Intervention\ImageManager(
            new Intervention\Drivers\Gd\Driver(),
        );
    }

    public function load(Image $image): ImageInterface
    {
        $imageResource = $image->readStream();
        $imageResource = Type\resource('stream')->coerce($imageResource);

        $path = $this->copyToTemporaryDirectory($imageResource, $image->extension());

        fclose($imageResource);

        return $this->manager->read($path);
    }

    /**
     * @param resource $baseImage
     *
     * @return non-empty-string
     */
    private function copyToTemporaryDirectory($baseImage, string $extension): string
    {
        $target = File\open_write_only(
            $path = $this->createTemporaryFile($extension),
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
     * @return non-empty-string
     */
    private function createTemporaryFile(string $extension): string
    {
        return Filesystem\create_temporary_file() . '.' . $extension;
    }
}
