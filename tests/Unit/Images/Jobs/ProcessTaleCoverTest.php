<?php

use App\Images\Cover;
use App\Images\Jobs\GenerateTaleCoverPlaceholder;
use App\Images\Jobs\GenerateTaleCoverVariants;
use App\Models\Tale;
use Illuminate\Queue\SerializableClosure;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use function Tests\fixture;

test('GenerateTaleCoverPlaceholder job works', function () {
    Storage::fake('testing');

    $filename = Str::random('10').'.jpg';

    // Photo by David Grandmougin on Unsplash
    $file = fopen(fixture('Images/cover.jpg'), 'r');

    Storage::cloud()->put("covers/original/{$filename}", $file, 'public');

    $tale = Tale::factory()->create([
        'cover' => null,
        'cover_placeholder' => null,
    ]);

    GenerateTaleCoverPlaceholder::dispatchSync(
        new Cover($filename),
        new SerializableClosure(
            fn (Cover $cover, string $placeholder) => $tale->forceFill([
                'cover' => $cover,
                'cover_placeholder' => $placeholder,
            ])->save(),
        ),
    );

    $tale->refresh();

    expect($tale->cover)->not->toBeNull()
        ->and($tale->cover->filename())->toBe($filename)
        ->and($tale->cover_placeholder)->toStartWith('data:image/svg+xml;base64,');
});

test('GenerateTaleCoverVariants job works', function () {
    Storage::fake('testing');

    $filename = Str::random('10').'.jpg';

    // Photo by David Grandmougin on Unsplash
    $file = fopen(fixture('Images/cover.jpg'), 'r');

    Storage::cloud()->put("covers/original/{$filename}", $file, 'public');

    GenerateTaleCoverVariants::dispatchSync(new Cover($filename));

    Storage::cloud()->assertExists("covers/128/{$filename}");
});
