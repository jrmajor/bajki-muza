<?php

namespace App\Images\Jobs;

use App\Images\Cover;
use App\Images\Values\FitMethod;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class GenerateTaleCoverPlaceholder implements ShouldQueue, ShouldBeUnique
{
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

    public function handle(): void
    {
        $sourceStream = Cover::disk()->readStream(
            $this->image->originalPath(),
        );

        $imageProcessor = new ImageProcessor($sourceStream);

        fclose($sourceStream);

        $this->image->update([
            'placeholder' => $imageProcessor->generateTinyJpg(FitMethod::Square),
        ]);
    }
}
