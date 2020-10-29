<?php

use App\Jobs\ProcessArtistPhoto;
use App\Models\Artist;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use function Tests\fixture;

it('works', function () {
    Storage::fake('testing');

    $filename = Str::random('10').'.jpg';

    // Photo by Alberto Bigoni on Unsplash
    $file = fopen(fixture('Images/photo.jpg'), 'r');

    Storage::cloud()->put("photos/original/$filename", $file, 'public');

    $artist = Artist::factory()
        ->create(['photo' => null]);

    $crop = [
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
    ];

    ProcessArtistPhoto::dispatchSync($artist, $filename, $crop);

    $artist->refresh();

    expect($artist->photo)->toBe($filename);

    expect($artist->photo_face_placeholder)
        ->toBe(file_get_contents(fixture('Images/photo_face_placeholder.b64')));

    expect($artist->photo_placeholder)
        ->toBe(file_get_contents(fixture('Images/photo_cropped_placeholder.b64')));

    expect($artist->photo_width)->toBe(529)
        ->and($artist->photo_height)->toBe(352);

    expect(Storage::cloud()->get("photos/112/$filename"))
        ->toBe(file_get_contents(fixture('Images/photo_face_112.jpg')));

    expect(Storage::cloud()->get("photos/160/$filename"))
        ->toBe(file_get_contents(fixture('Images/photo_cropped_160.jpg')));
});
