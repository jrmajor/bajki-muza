<?php

namespace App\Images\Jobs;

use App\Images\Image;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Intervention\Image\Decoders\BinaryImageDecoder;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\ImageManagerInterface;
use Psl\Encoding\Base64;

class GenerateImageVariants implements ShouldBeUnique, ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public function __construct(
        protected Image $image,
    ) { }

    public function uniqueId(): string
    {
        return $this->image->filename();
    }

    public function handle(): void
    {
        foreach ($this->image::variants() as $variant) {
            $this->processVariant($variant);
        }
    }

    public function processVariant(string $variant): void
    {
        $image = $this->loadImage();

        $this->image->processVariant($image, $variant);

        if ($variant === 'default' && $this->image::shouldSaveDimensions()) {
            /** @phpstan-ignore property.notFound */
            $this->image->width = $image->width();
            /** @phpstan-ignore property.notFound */
            $this->image->height = $image->height();
        }

        $destinationPath = $this->image->path($variant);
        $this->image::disk()->put(
            path: $destinationPath,
            contents: (string) $image->encodeByPath($destinationPath),
            options: 'public',
        );

        $this->image->setAttribute(
            ($variant === 'default' ? '' : "{$variant}_") . 'placeholder',
            $this->generatePlaceholder($image),
        );

        $this->image->save();
    }

    /**
     * @return non-empty-string
     */
    public function generatePlaceholder(ImageInterface $image): string
    {
        $dataUri = $image->scale(height: 32)->blur()->toJpeg()->toDataUri();

        $svg = view('placeholderSvg', [
            'width' => $image->width(),
            'height' => $image->height(),
            'dataUri' => $dataUri,
        ]);

        return 'data:image/svg+xml;base64,' . Base64\encode($svg);
    }

    private function loadImage(): ImageInterface
    {
        $manager = app(ImageManagerInterface::class);
        $contents = $this->image::disk()->get($this->image->originalPath());

        return $manager->read($contents, BinaryImageDecoder::class);
    }
}
