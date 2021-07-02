<?php

use App\Images\Jobs\GenerateArtistPhotoPlaceholders;
use App\Images\Jobs\GenerateArtistPhotoVariants;
use App\Images\Photo;
use App\Images\Values\ArtistPhotoCrop;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

use function Tests\fixture;

beforeEach(function () {
    $this->crop = ArtistPhotoCrop::fake();
});

it('GenerateArtistPhotoPlaceholders', function () {
    Storage::fake('testing');

    $filename = Str::random('10') . '.jpg';

    // Photo by Alberto Bigoni on Unsplash
    $file = fopen(fixture('Images/photo.jpg'), 'r');

    Photo::disk()->put("photos/original/{$filename}", $file, 'public');

    fclose($file);

    GenerateArtistPhotoPlaceholders::dispatchSync(
        $photo = Photo::create([
            'filename' => $filename,
            'crop' => $this->crop,
        ]),
    );

    $photo->refresh();

    expect($photo)->not->toBeNull()
        ->and($photo->filename())->toBe($filename)
        ->and($photo->width)->toBe(529)
        ->and($photo->height)->toBe(352)
        ->and($photo->crop)->not->toBeNull()
        ->and((string) $photo->crop)->toEqual((string) $this->crop)
        ->and($photo->face_placeholder)->toStartWith('data:image/svg+xml;base64,')
        ->and($photo->placeholder)->toStartWith('data:image/svg+xml;base64,');
});

test('GenerateArtistPhotoVariants job works', function () {
    Storage::fake('testing');

    $filename = Str::random('10') . '.jpg';

    // Photo by Alberto Bigoni on Unsplash
    $file = fopen(fixture('Images/photo.jpg'), 'r');

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
});
