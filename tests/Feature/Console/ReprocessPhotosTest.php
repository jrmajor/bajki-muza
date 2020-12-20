<?php

use App\Images\Jobs\GenerateArtistPhotoPlaceholders;
use App\Images\Jobs\GenerateArtistPhotoVariants;
use App\Images\Values\ArtistPhotoCrop;
use App\Models\Artist;
use Illuminate\Support\Facades\Queue;
use function Pest\Laravel\artisan;
use function Tests\fixture;

it('throws error when artist does not exist')
    ->artisan('reprocess:photos --artist nonexistent-artist')
    ->expectsOutput('Artist does not exist.')
    ->assertExitCode(1);

it('throws error when artist does not have a photo', function () {
    Artist::factory()->create([
        'name' => 'Test Artist',
        'photo' => null,
    ]);

    artisan('reprocess:photos --artist test-artist')
        ->expectsOutput('Artist does not have a photo.')
        ->assertExitCode(1);
});

it('works with single artist', function () {
    Storage::fake('testing');

    Artist::factory()->create([
        'name' => 'Test Artist',
        'photo' => 'test.jpg',
        'photo_crop' => new ArtistPhotoCrop([
            'face' => [
                'x' => 181,
                'y' => 246,
                'size' => 189,
            ],
            'image' => [
                'x' => 79,
                'y' => 247,
                'width' => 529,
                'height' => 352,
            ],
        ]),
    ]);

    // Photo by David Grandmougin on Unsplash
    $file = fopen(fixture('Images/photo.jpg'), 'r');

    Storage::cloud()->put('photos/original/test.jpg', $file, 'public');

    Queue::fake();

    artisan('reprocess:photos --artist test-artist')
        ->assertExitCode(0);

    Queue::assertPushedWithChain(
        GenerateArtistPhotoPlaceholders::class,
        [GenerateArtistPhotoVariants::class],
    );
});

it('asks for confirmation when processing all photos')
    ->artisan('reprocess:photos')
    ->expectsConfirmation('Do you want to reprocess all photos?', 'no')
    ->assertExitCode(1);
