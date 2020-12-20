<?php

namespace Tests\Unit\Images;

use App\Images\Cover;
use App\Images\Jobs\GenerateTaleCoverPlaceholder;
use App\Images\Jobs\GenerateTaleCoverVariants;
use Exception;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Storage;

it('can get list of sizes', function () {
    expect(Cover::sizes())->toHaveCount(8);

    Cover::sizes()->each(function ($size) {
        expect($size)->toBeInt();
    });
});

it('stores new cover in correct path and dispatches necessary jobs to process it', function () {
    Storage::fake('testing');

    Queue::fake();

    $image = Cover::store(
        UploadedFile::fake()->image('test.jpg'),
        fn (Cover $cover, string $placeholder) => throw new Exception('Queue is mocked anyway.'),
    );

    expect($image)->toBeInstanceOf(Cover::class)
        ->and($image->filename())->toEndWith('.jpg');

    expect(Storage::cloud()->files('covers/original'))->toHaveCount(1);

    Queue::assertPushedWithChain(
        GenerateTaleCoverPlaceholder::class,
        [GenerateTaleCoverVariants::class],
    );
});

it('returns correct original path', function () {
    expect(
        (new Cover('test.jpg'))->originalPath(),
    )->toBe('covers/original/test.jpg');
});

it('returns correct path for given size', function () {
    expect(
        (new Cover('test.jpg'))->path(384),
    )->toBe('covers/384/test.jpg');
});
