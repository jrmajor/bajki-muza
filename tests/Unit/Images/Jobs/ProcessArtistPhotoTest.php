<?php

use App\Images\Jobs\GenerateArtistPhotoPlaceholders;
use App\Images\Jobs\GenerateArtistPhotoVariants;
use App\Images\Photo;
use App\Images\Values\ArtistPhotoCrop;
use App\Models\Artist;
use Illuminate\Queue\SerializableClosure;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use function Tests\fixture;

beforeEach(function () {
    $this->crop = new ArtistPhotoCrop([
        'face' => [
            'x' => 181,
            'y' => 246,
            'size' => 189,
        ],
        'image'=> [
            'x' => 79,
            'y' => 247,
            'width' => 529,
            'height' => 352,
        ],
    ]);
});

it('GenerateArtistPhotoPlaceholders', function () {
    Storage::fake('testing');

    $filename = Str::random('10').'.jpg';

    // Photo by Alberto Bigoni on Unsplash
    $file = fopen(fixture('Images/photo.jpg'), 'r');

    Storage::cloud()->put("photos/original/{$filename}", $file, 'public');

    $artist = Artist::factory()->create([
        'photo' => null,
        'photo_width' => null,
        'photo_height' => null,
        'photo_crop' => null,
        'photo_face_placeholder' => null,
        'photo_placeholder' => null,
    ]);

    GenerateArtistPhotoPlaceholders::dispatchSync(
        new Photo($filename, $this->crop),
        new SerializableClosure(
            function (
                Photo $photo,
                int $width,
                int $height,
                string $placeholder,
                string $facePlaceholder,
            ) use ($artist) {
                $artist->forceFill([
                    'photo' => $photo,
                    'photo_width' => $width,
                    'photo_height' => $height,
                    'photo_crop' => $photo->crop(),
                    'photo_face_placeholder' => $facePlaceholder,
                    'photo_placeholder' => $placeholder,
                ])->save();
            },
        ),
    );

    $artist->refresh();

    expect($artist->photo)->not->toBeNull()
        ->and($artist->photo->filename())->toBe($filename)
        ->and($artist->photo_width)->toBe(529)
        ->and($artist->photo_height)->toBe(352)
        ->and($artist->photo_crop)->not->toBeNull()
        ->and($artist->photo_crop->toArray())->toEqual($this->crop->toArray())
        ->and($artist->photo_face_placeholder)->toStartWith('data:image/svg+xml;base64,')
        ->and($artist->photo_placeholder)->toStartWith('data:image/svg+xml;base64,');
});

test('GenerateArtistPhotoVariants job works', function () {
    Storage::fake('testing');

    $filename = Str::random('10').'.jpg';

    // Photo by Alberto Bigoni on Unsplash
    $file = fopen(fixture('Images/photo.jpg'), 'r');

    Storage::cloud()->put("photos/original/{$filename}", $file, 'public');

    GenerateArtistPhotoVariants::dispatchSync(
        new Photo($filename, $this->crop),
    );

    Storage::cloud()->assertExists("photos/160/{$filename}");

    Storage::cloud()->assertExists("photos/56/{$filename}");
});
