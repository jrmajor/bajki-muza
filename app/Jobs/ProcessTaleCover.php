<?php

namespace App\Jobs;

use App\Tale;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Filesystem\Factory as Storage;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Spatie\Image\Image;
use Spatie\Image\Manipulations;
use Spatie\TemporaryDirectory\TemporaryDirectory;

class ProcessTaleCover implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $tale;

    protected $sizes = [
        60, // 3.75rem
        90, // 3.75rem * 1.5
        120, // 3.75rem * 2
        128, // 8rem
        160, // 10rem
        192, // 8rem * 1.5
        240, // 10rem * 1.5
        256, // 8rem * 2
        320, // 10rem * 2
    ];

    public function __construct(Tale $tale)
    {
        $this->tale = $tale;
    }

    public function handle(Storage $storage): void
    {
        $this->storage = $storage;

        $temporaryDirectory = (new TemporaryDirectory)->create();

        $sourceFile = "covers/original/{$this->tale->cover}";

        $baseImagePath = $this->copyToTemporaryDirectory($sourceFile, $temporaryDirectory);

        $this->tale->cover_placeholder = $this->generateTinyJpg($baseImagePath, $temporaryDirectory);

        $this->tale->save();

        foreach ($this->sizes as $size) {
            $this->generateResponsiveImage($baseImagePath, $size, $temporaryDirectory);
        }

        $temporaryDirectory->delete();
    }

    public function copyToTemporaryDirectory($source, $temporaryDirectory): string
    {
        $targetFile = $temporaryDirectory->path($this->tale->cover);

        touch($targetFile);

        $stream = $this->storage->cloud()->readStream($source);

        $targetFileStream = fopen($targetFile, 'a');

        while (! feof($stream)) {
            $chunk = fgets($stream, 1024);
            fwrite($targetFileStream, $chunk);
        }

        fclose($stream);

        fclose($targetFileStream);

        return $targetFile;
    }

    public function generateTinyJpg(
        string $baseImagePath,
        TemporaryDirectory $temporaryDirectory
    ): string {
        $responsiveImageName = $this->appendToFileName(
            $this->tale->cover,
            "_tiny"
        );

        $tempDestination = $temporaryDirectory->path($responsiveImageName);

        Image::load($baseImagePath)
            ->fit(Manipulations::FIT_CROP, 32, 32)
            ->blur(5)
            ->save($tempDestination);

        $tinyImageDataBase64 = base64_encode(file_get_contents($tempDestination));

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
        TemporaryDirectory $temporaryDirectory
    ): void {
        $responsiveImageName = $this->appendToFileName(
            $this->tale->cover,
            "_{$targetSize}"
        );

        $responsiveImagePath = $temporaryDirectory->path($responsiveImageName);

        Image::load($baseImagePath)
            ->optimize()
            ->fit(Manipulations::FIT_CROP, $targetSize, $targetSize)
            ->save($responsiveImagePath);

        $file = fopen($responsiveImagePath, 'r');

        $this->storage->cloud()
            ->put("covers/{$targetSize}/{$this->tale->cover}", $file, 'public');
    }

    protected function appendToFileName(string $filePath, string $suffix): string
    {
        $baseName = pathinfo($filePath, PATHINFO_FILENAME);

        $extension = pathinfo($filePath, PATHINFO_EXTENSION);

        return "{$baseName}{$suffix}.{$extension}";
    }
}
