<?php

namespace Tests\Unit\Images;

use App\Images\Jobs\GenerateArtistPhotoPlaceholders;
use App\Images\Jobs\GenerateArtistPhotoVariants;
use App\Images\Photo;
use Exception;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Storage;

it('can get lists of full image sizes', function () {
    expect(Photo::imageSizes())->toHaveCount(3);

    Photo::imageSizes()->each(function ($size) {
        expect($size)->toBeInt();
    });
});

it('can get lists of cropped to face image sizes', function () {
    expect(Photo::faceSizes())->toHaveCount(3);

    Photo::faceSizes()->each(function ($size) {
        expect($size)->toBeInt();
    });
});

it('can get lists of sizes', function () {
    expect(Photo::sizes())->toHaveCount(6);

    Photo::sizes()->each(function ($size) {
        expect($size)->toBeInt();
    });
});

it('stores new photo in correct path and dispatches necessary jobs to process it', function () {
    Storage::fake('testing');

    Queue::fake();

    $image = Photo::store(
        UploadedFile::fake()->image('test.png'),
        fn (Photo $photo, string $placeholder) => throw new Exception('Queue is mocked anyway.'),
    );

    expect($image)->toBeInstanceOf(Photo::class)
        ->and($image->filename())->toEndWith('.png');

    expect(Storage::cloud()->files('photos/original'))->toHaveCount(1);

    Queue::assertPushedWithChain(
        GenerateArtistPhotoPlaceholders::class,
        [GenerateArtistPhotoVariants::class],
    );
});

it('returns correct original path', function () {
    expect(
        (new Photo('test.jpg'))->originalPath(),
    )->toBe('photos/original/test.jpg');
});

it('returns correct path for given size', function () {
    expect(
        (new Photo('test.jpg'))->path(384),
    )->toBe('photos/384/test.jpg');
});
