<?php

use App\Images\Cover;
use App\Images\Jobs\GenerateTaleCoverPlaceholder;
use App\Images\Jobs\GenerateTaleCoverVariants;
use App\Images\Photo;
use App\Models\Tale;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Storage;

it('can get list of sizes')
    ->expect(Cover::sizes()->all())
    ->toBe([60, 90, 120, 128, 192, 288, 256, 384]);

it('stores new cover in correct path and dispatches necessary jobs to process it', function () {
    Storage::fake('testing');

    Queue::fake();

    $image = Cover::store(
        UploadedFile::fake()->image('test.jpg'),
    );

    expect($image)->toBeInstanceOf(Cover::class)
        ->and($image->filename())->toEndWith('.jpg');

    expect(Photo::disk()->files('covers/original'))->toHaveCount(1);

    Queue::assertPushedWithChain(
        GenerateTaleCoverPlaceholder::class,
        [GenerateTaleCoverVariants::class],
    );
});

it('returns correct original path', function () {
    expect(
        (new Cover(['filename' => 'test.jpg']))->originalPath(),
    )->toBe('covers/original/test.jpg');
});

it('returns correct path for given size', function () {
    expect(
        (new Cover(['filename' => 'test.jpg']))->path(384),
    )->toBe('covers/384/test.jpg');
});

it('can get its tales', function () {
    $cover = Cover::create([
        'filename' => 'tXySLaaEbhfyzLXm6QggZY5VSFulyN2xLp4OgYSy.png',
    ]);

    $tales = Tale::factory()
        ->count(2)
        ->cover($cover)
        ->create();

    // @todo $cover->refesh();
    $cover = $cover->fresh();

    expect($cover->tales)->toHaveCount(2)
        ->and($cover->tales[0])->toBeModel($tales[0])
        ->and($cover->tales[1])->toBeModel($tales[1]);
});
