<?php

namespace App\Images\Jobs;

use App\Images\Image;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Psl\Filesystem;
use Psl\Str;

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

    public function handle(ImageLoader $imageLoader): void
    {
        foreach ($this->image::variants() as $variant) {
            $image = $imageLoader->load($this->image);

            $variantImage = $this->image->processVariant($image, $variant)->save();
            $localPath = $variantImage->origin()->filePath();
            assert(is_string($localPath));

            $destinationPath = $this->image->path($variant);

            $this->image::disk()->putFileAs(
                path: $dirname = Filesystem\get_directory($destinationPath),
                file: $localPath,
                name: Str\after($destinationPath, $dirname),
                options: 'public',
            );

            Filesystem\delete_file($localPath);
        }
    }
}
