<?php

namespace Tests\Unit\Images\Jobs;

use App\Images\Cover;
use App\Images\Jobs\ProcessImage;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use PHPUnit\Framework\Attributes\TestDox;
use Tests;
use Tests\TestCase;

final class CoverProcessingTest extends TestCase
{
    private string $filename;

    private Cover $cover;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('testing');

        $this->filename = Str::random(10) . '.jpg';

        // Photo by Alberto Bigoni on Unsplash
        Cover::disk()->put(
            "covers/original/{$this->filename}",
            Tests\read_fixture('Images/cover.jpg'),
        );

        $this->cover = Cover::create(['filename' => $this->filename]);
    }

    #[TestDox('it generates variants and placeholders')]
    public function testItWorks(): void
    {
        Cover::disk()->assertExists("covers/original/{$this->filename}");
        Cover::disk()->assertMissing("covers/default/{$this->filename}");
        $this->assertNull($this->cover->placeholder());

        ProcessImage::dispatchSync($this->cover);

        $this->cover->refresh();
        Cover::disk()->assertExists("covers/original/{$this->filename}");
        Cover::disk()->assertExists("covers/default/{$this->filename}");
        $this->assertStringStartsWith('data:image/svg+xml;base64,', $this->cover->placeholder());
    }
}
