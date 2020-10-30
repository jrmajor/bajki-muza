<?php

namespace App\Jobs;

use App\Models\Artist;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Spatie\Image\Image;
use Spatie\TemporaryDirectory\TemporaryDirectory;

class ProcessArtistPhoto implements ShouldQueue
{
    use ProcessesImages, Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected Artist $artist;

    protected string $filename;

    protected array $crop;

    public static $faceSizes = [
        56, // 3.5rem
        84, // 3.5rem * 1.5
        112, // 3.5rem * 2
    ];

    public static $imageSizes = [
        160, // 10rem
        240, // 10rem * 1.5
        320, // 10rem * 2
    ];

    public function __construct(Artist $artist, string $filename, array $crop)
    {
        $this->artist = $artist;

        $this->filename = $filename;

        $this->crop = $crop;
    }

    public function handle(): void
    {
        $temporaryDirectory = (new TemporaryDirectory)->create();

        $sourceFile = "photos/original/{$this->filename}";

        $sourceStream = Storage::cloud()->readStream($sourceFile);

        $baseImagePath = $this->copyToTemporaryDirectory($sourceStream, $temporaryDirectory, $this->filename);

        $croppedFacePath = $this->cropFace($baseImagePath, $temporaryDirectory);

        $croppedImagePath = $this->cropImage($baseImagePath, $temporaryDirectory);

        $croppedImage = Image::load($croppedImagePath);

        $this->artist->forceFill([
            'photo' => $this->filename,
            'photo_width' => $croppedImage->getWidth(),
            'photo_height' => $croppedImage->getHeight(),
            'photo_crop' => $this->crop,
            'photo_face_placeholder' => $this->generateTinyJpg($croppedFacePath, 'square', $temporaryDirectory),
            'photo_placeholder' => $this->generateTinyJpg($croppedImagePath, 'height', $temporaryDirectory),
        ])->save();

        foreach (self::$faceSizes as $size) {
            $responsiveImagePath = $this->generateResponsiveImage(
                                            $croppedFacePath,
                                            $size,
                                            'square',
                                            $temporaryDirectory,
                                        );

            $file = fopen($responsiveImagePath, 'r');

            Storage::cloud()
                ->put("photos/{$size}/{$this->filename}", $file, 'public');
        }

        foreach (self::$imageSizes as $size) {
            $responsiveImagePath = $this->generateResponsiveImage(
                                            $croppedImagePath,
                                            $size,
                                            'height',
                                            $temporaryDirectory,
                                        );

            $file = fopen($responsiveImagePath, 'r');

            Storage::cloud()
                ->put("photos/{$size}/{$this->filename}", $file, 'public');
        }

        $temporaryDirectory->delete();
    }

    public function cropFace(
        string $baseImagePath,
        TemporaryDirectory $temporaryDirectory
    ): string {
        $croppedFaceName = $this->appendToFileName($baseImagePath, '_face');

        $croppedFacePath = $temporaryDirectory->path($croppedFaceName);

        Image::load($baseImagePath)
            ->manualCrop(
                $this->crop['face']['size'],
                $this->crop['face']['size'],
                $this->crop['face']['x'],
                $this->crop['face']['y'],
            )
            ->save($croppedFacePath);

        return $croppedFacePath;
    }

    public function cropImage(
        string $baseImagePath,
        TemporaryDirectory $temporaryDirectory
    ): string {
        $croppedImageName = $this->appendToFileName($baseImagePath, '_image');

        $croppedImagePath = $temporaryDirectory->path($croppedImageName);

        Image::load($baseImagePath)
            ->manualCrop(
                $this->crop['image']['width'],
                $this->crop['image']['height'],
                $this->crop['image']['x'],
                $this->crop['image']['y'],
            )
            ->save($croppedImagePath);

        return $croppedImagePath;
    }
}
