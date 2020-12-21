<?php

use App\Images\Jobs\GenerateArtistPhotoPlaceholders;
use App\Images\Jobs\GenerateArtistPhotoVariants;
use App\Images\Photo;
use App\Images\Values\ArtistPhotoCrop;
use App\Models\Artist;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Storage;

it('can get lists of full image sizes')
    ->expect(Photo::imageSizes()->all())
    ->toBe([160, 240, 320]);

it('can get lists of cropped to face image sizes')
    ->expect(Photo::faceSizes()->all())
    ->toBe([56, 84, 112]);

it('can get lists of sizes')
    ->expect(Photo::sizes()->all())
    ->toBe([56, 84, 112, 160, 240, 320]);

it('casts dimensions to integers')
    ->expect((new Photo(['width' => '2137']))->width)->toBe(2137)
    ->expect((new Photo(['height' => '2115']))->height)->toBe(2115);

it('casts crop to ArtistPhotoCrop', function () {
    $photo = Photo::create([
        'filename' => 'test.jpg',
        'crop' => ArtistPhotoCrop::fake(),
    ])->refresh();

    expect($photo->crop)->toBeInstanceOf(ArtistPhotoCrop::class)
        ->and($photo->crop->toArray())->toBe(ArtistPhotoCrop::fake()->toArray());
});

it('stores new photo in correct path and dispatches necessary jobs to process it', function () {
    Storage::fake('testing');

    Queue::fake();

    $image = Photo::store(
        UploadedFile::fake()->image('test.png'),
        ['crop' => ArtistPhotoCrop::fake()],
    );

    expect($image)->toBeInstanceOf(Photo::class)
        ->and($image->filename())->toEndWith('.png')
        ->and($image->crop()->toArray())->toBe(ArtistPhotoCrop::fake()->toArray());

    expect(Photo::disk()->files('photos/original'))->toHaveCount(1);

    Queue::assertPushedWithChain(
        GenerateArtistPhotoPlaceholders::class,
        [GenerateArtistPhotoVariants::class],
    );
});

it('returns correct original path', function () {
    expect(
        (new Photo(['filename' => 'test.jpg']))->originalPath(),
    )->toBe('photos/original/test.jpg');
});

it('returns correct path for given size', function () {
    expect(
        (new Photo(['filename' => 'test.jpg']))->path(384),
    )->toBe('photos/384/test.jpg');
});

it('can get face placeholder')
    ->expect(
        (new Photo(['face_placeholder' => 'test placeholder']))->facePlaceholder(),
    )->toBe('test placeholder');

it('can set and get crop', function () {
     $photo = Photo::create([
         'filename' => 'test.jpg',
         'crop' => $oldCrop = ArtistPhotoCrop::fake(),
     ]);

     expect($photo->crop()->toArray())
         ->toBe($newCrop = $oldCrop->toArray());

     Arr::set($newCrop, 'face.size', 190);

     expect(Arr::get($newCrop, 'face.size'))->toBe(190);

     $photo->setCrop(new ArtistPhotoCrop($newCrop));

     expect($photo->refresh()->crop()->toArray())->toBe($newCrop);
});

it('can calculate aspect ratio')
    ->expect(
        (new Photo([
            'width' => 16,
            'height' => 10,
        ]))->aspectRatio(),
    )->toBe(1.6);

test('aspect ratio is null, when dimensions are not set')
    ->expect(
        (new Photo([
            'width' => null,
            'height' => null,
        ]))->aspectRatio(),
    )->toBeNull();

it('can get its tales', function () {
    $photo = Photo::create([
        'filename' => 'tXySLaaEbhfyzLXm6QggZY5VSFulyN2xLp4OgYSy.png',
        'crop' => ArtistPhotoCrop::fake(),
    ]);

    $photos = Artist::factory()
        ->count(2)
        ->photo($photo)
        ->create();

    // @todo $photo->refesh();
    $photo = $photo->fresh();

    expect($photo->artists)->toHaveCount(2)
        ->and($photo->artists[0]->is($photos[0]))->toBeTrue()
        ->and($photo->artists[1]->is($photos[1]))->toBeTrue();
});
