<?php

namespace Tests\Unit\Images\Jobs;

use App\Images\Jobs\GenerateImageVariants;
use App\Images\Photo;
use App\Images\Values\ArtistPhotoCrop;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use PHPUnit\Framework\Attributes\TestDox;
use Tests;
use Tests\TestCase;

final class PhotoProcessingTest extends TestCase
{
    private string $filename;

    private Photo $photo;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('testing');

        $this->filename = Str::random(10) . '.jpg';

        // Photo by Alberto Bigoni on Unsplash
        Photo::disk()->put(
            "photos/original/{$this->filename}",
            Tests\read_fixture('Images/photo.jpg'),
        );

        $this->photo = Photo::create([
            'filename' => $this->filename,
            'crop' => ArtistPhotoCrop::fake(),
        ]);
    }

    #[TestDox('it generates variants and placeholders')]
    public function testItWorks(): void
    {
        Photo::disk()->assertExists("photos/original/{$this->filename}");
        Photo::disk()->assertMissing("photos/default/{$this->filename}");
        Photo::disk()->assertMissing("photos/face/{$this->filename}");
        $this->assertNull($this->photo->placeholder());
        $this->assertNull($this->photo->placeholder('face'));

        GenerateImageVariants::dispatchSync($this->photo);

        $this->photo->refresh();
        Photo::disk()->assertExists("photos/original/{$this->filename}");
        Photo::disk()->assertExists("photos/default/{$this->filename}");
        Photo::disk()->assertExists("photos/face/{$this->filename}");
        $this->assertSame(529, $this->photo->width);
        $this->assertSame(352, $this->photo->height);
        $this->assertStringStartsWith('data:image/svg+xml;base64,', $this->photo->placeholder());
        $this->assertStringStartsWith('data:image/svg+xml;base64,', $this->photo->placeholder('face'));
    }
}
