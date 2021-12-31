<?php

namespace Tests\Unit\Images\Jobs;

use App\Images\Cover;
use App\Images\Jobs\GenerateTaleCoverPlaceholder;
use App\Images\Jobs\GenerateTaleCoverVariants;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use PHPUnit\Framework\Attributes\TestDox;
use Tests;
use Tests\TestCase;

final class ProcessTaleCoverTest extends TestCase
{
    #[TestDox('GenerateTaleCoverPlaceholder job works')]
    public function testPlaceholders(): void
    {
        Storage::fake('testing');

        $filename = Str::random('10') . '.jpg';

        // Photo by David Grandmougin on Unsplash
        $file = fopen(Tests\fixture('Images/cover.jpg'), 'r');

        Cover::disk()->put("covers/original/{$filename}", $file, 'public');

        fclose($file);

        GenerateTaleCoverPlaceholder::dispatchSync(
            $cover = Cover::create(['filename' => $filename]),
        );

        $cover->refresh();

        $this->assertSame($filename, $cover->filename());
        $this->assertStringStartsWith('data:image/svg+xml;base64,', $cover->placeholder());
    }

    #[TestDox('GenerateTaleCoverVariants job works')]
    public function testVariants(): void
    {
        Storage::fake('testing');

        $filename = Str::random('10') . '.jpg';

        // Photo by David Grandmougin on Unsplash
        $file = fopen(Tests\fixture('Images/cover.jpg'), 'r');

        Cover::disk()->put("covers/original/{$filename}", $file, 'public');

        fclose($file);

        GenerateTaleCoverVariants::dispatchSync(
            Cover::create(['filename' => $filename]),
        );

        Cover::disk()->assertExists("covers/128/{$filename}");
    }
}
