<?php

use App\Jobs\ProcessTaleCover;
use App\Models\Tale;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use function Tests\fixture;

it('works', function () {
    Storage::fake('testing');

    $filename = Str::random('10').'.jpg';

    // Photo by David Grandmougin on Unsplash
    $file = fopen(fixture('Images/cover.jpg'), 'r');

    Storage::cloud()->put("covers/original/$filename", $file, 'public');

    $tale = Tale::factory()
        ->create(['cover' => null]);

    ProcessTaleCover::dispatchSync($tale, $filename);

    $tale->refresh();

    expect($tale->cover)->toBe($filename);

    expect($tale->cover_placeholder)
        ->toBe(file_get_contents(fixture('Images/cover_placeholder.b64')));

    expect(Storage::cloud()->get("covers/128/$filename"))
        ->toBe(file_get_contents(fixture('Images/cover_128.jpg')));
});
