<?php

namespace App\Images\Jobs;

use App\Images\Cover;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Spatie\TemporaryDirectory\TemporaryDirectory;

class GenerateTaleCoverPlaceholder implements ShouldQueue, ShouldBeUnique
{
    use ProcessesImages;
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public function __construct(
        protected Cover $image,
    ) { }

    public function uniqueId(): string
    {
        return $this->image->filename();
    }

    public function handle()
    {
        $this->temporaryDirectory = (new TemporaryDirectory())->create();

        $sourceStream = Cover::disk()->readStream(
            $this->image->originalPath(),
        );

        $baseImagePath = $this->copyToTemporaryDirectory(
            $sourceStream, $this->image->filename(),
        );

        $this->image->update([
            'placeholder' => $this->generateTinyJpg($baseImagePath, 'square'),
        ]);

        $this->temporaryDirectory->delete()
            ?: throw new Exception('Failed to delete temporary directory.');
    }
}
