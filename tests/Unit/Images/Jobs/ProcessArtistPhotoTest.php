<?php

namespace Tests\Unit\Images\Jobs;

use App\Images\Jobs\GenerateArtistPhotoPlaceholders;
use App\Images\Jobs\GenerateArtistPhotoVariants;
use App\Images\Photo;
use App\Images\Values\ArtistPhotoCrop;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use PHPUnit\Framework\Attributes\TestDox;
use Tests;
use Tests\TestCase;

final class ProcessArtistPhotoTest extends TestCase
{
    private ArtistPhotoCrop $crop;

    protected function setUp(): void
    {
        parent::setUp();

        $this->crop = ArtistPhotoCrop::fake();
    }

    #[TestDox('GenerateArtistPhotoPlaceholders job works')]
    public function testPlaceholders(): void
    {
        Storage::fake('testing');

        $filename = Str::random('10') . '.jpg';

        // Photo by Alberto Bigoni on Unsplash
        $file = fopen(Tests\fixture('Images/photo.jpg'), 'r');

        Photo::disk()->put("photos/original/{$filename}", $file, 'public');

        fclose($file);

        GenerateArtistPhotoPlaceholders::dispatchSync(
            $photo = Photo::create([
                'filename' => $filename,
                'crop' => $this->crop,
            ]),
        );

        $photo->refresh();

        $this->assertSame($filename, $photo->filename());
        $this->assertSame(529, $photo->width);
        $this->assertSame(352, $photo->height);
        $this->assertNotNull($photo->crop());
        $this->assertSame($photo->crop()->toJson(), $this->crop->toJson());
        $this->assertStringStartsWith('data:image/svg+xml;base64,', $photo->facePlaceholder());
        $this->assertStringStartsWith('data:image/svg+xml;base64,', $photo->placeholder());
    }

    #[TestDox('GenerateArtistPhotoVariants job works')]
    public function testVariants(): void
    {
        Storage::fake('testing');

        $filename = Str::random('10') . '.jpg';

        // Photo by Alberto Bigoni on Unsplash
        $file = fopen(Tests\fixture('Images/photo.jpg'), 'r');

        Photo::disk()->put("photos/original/{$filename}", $file, 'public');

        fclose($file);

        GenerateArtistPhotoVariants::dispatchSync(
            Photo::create([
                'filename' => $filename,
                'crop' => $this->crop,
            ]),
        );

        Photo::disk()->assertExists("photos/160/{$filename}");

        Photo::disk()->assertExists("photos/56/{$filename}");
    }
}
