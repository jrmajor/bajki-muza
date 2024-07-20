<?php

namespace Tests\Unit\Images\Jobs;

use App\Images\Jobs\GenerateImageVariants;
use App\Images\Photo;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use PHPUnit\Framework\Attributes\TestDox;
use Tests;
use Tests\TestCase;
use Tests\Unit\Images\TestCover;

final class ImageProcessorsTest extends TestCase
{
    #[TestDox('GenerateImageVariantsTest job works')]
    public function testVariants(): void
    {
        Storage::fake('testing');

        $filename = Str::random(10) . '.jpg';

        // Photo by Alberto Bigoni on Unsplash
        TestCover::disk()->put(
            "covers/original/{$filename}",
            Tests\read_fixture('Images/cover.jpg'),
        );

        TestCover::disk()->assertExists("covers/original/{$filename}");
        TestCover::disk()->assertMissing("covers/default/{$filename}");

        GenerateImageVariants::dispatchSync(
            TestCover::create(['filename' => $filename]),
        );

        TestCover::disk()->assertExists("covers/original/{$filename}");
        TestCover::disk()->assertExists("covers/default/{$filename}");
    }
}
