<?php

use App\Images\Cover;
use App\Images\Jobs\GenerateTaleCoverPlaceholder;
use App\Images\Jobs\GenerateTaleCoverVariants;
use Illuminate\Support\Str;
use function Tests\fixture;

test('GenerateTaleCoverPlaceholder job works', function () {
    Storage::fake('testing');

    $filename = Str::random('10').'.jpg';

    // Photo by David Grandmougin on Unsplash
    $file = fopen(fixture('Images/cover.jpg'), 'r');

    Cover::disk()->put("covers/original/{$filename}", $file, 'public');

    GenerateTaleCoverPlaceholder::dispatchSync(
        $cover = Cover::create(['filename' => $filename]),
    );

    $cover->refresh();

    expect($cover->filename())->toBe($filename)
        ->and($cover->placeholder)->toStartWith('data:image/svg+xml;base64,');
});

test('GenerateTaleCoverVariants job works', function () {
    Storage::fake('testing');

    $filename = Str::random('10').'.jpg';

    // Photo by David Grandmougin on Unsplash
    $file = fopen(fixture('Images/cover.jpg'), 'r');

    Cover::disk()->put("covers/original/{$filename}", $file, 'public');

    GenerateTaleCoverVariants::dispatchSync(
        Cover::create(['filename' => $filename]),
    );

    Cover::disk()->assertExists("covers/128/{$filename}");
});
