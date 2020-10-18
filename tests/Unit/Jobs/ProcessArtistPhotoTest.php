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

    ProcessArtistPhoto::dispatchSync($artist, $filename);

    $artist->refresh();

    expect($artist->photo)->toBe($filename);

    expect($artist->photo_placeholder)
        ->toBe(file_get_contents(fixture('Images/photo_placeholder.b64')));

    expect($artist->photo_width)->toBe(640)
        ->and($artist->photo_height)->toBe(964);

    expect(Storage::cloud()->get("photos/112/$filename"))
        ->toBe(file_get_contents(fixture('Images/photo_112.jpg')));
});
