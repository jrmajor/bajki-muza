<?php

use App\Images\Jobs\GenerateArtistPhotoPlaceholders;
use App\Images\Jobs\GenerateArtistPhotoVariants;
use App\Images\Photo;
use App\Images\Values\ArtistPhotoCrop;
use App\Models\Artist;
use Illuminate\Http\UploadedFile;

use function Tests\asUser;
use function Tests\fixture;

beforeEach(function () {
    $this->attributes = [
        'slug' => 'ilona-kusmierska',
        'name' => 'Ilona Kuśmierska',
        'discogs' => 602488,
        'filmpolski' => 11623,
        'wikipedia' => 'Ilona_Kuśmierska',
    ];

    $this->crop = ArtistPhotoCrop::fake();

    $this->rawCrop = $this->crop->toArray();

    $this->artist = Artist::factory()
        ->noPhoto()->create($this->attributes);
});

test('photo can be deleted', function () {
    $this->artist->photo()->associate(
        Photo::create([
            'filename' => 'test.jpg',
            'crop' => ArtistPhotoCrop::fake(),
        ]),
    )->save();

    // @todo $this->artist->refresh();
    $this->artist = $this->artist->fresh();

    expect($this->artist->photo)->not->toBeNull();

    asUser()
        ->put(
            "artysci/{$this->artist->slug}",
            array_merge($this->attributes, [
                'remove_photo' => true,
            ]),
        )->assertRedirect("artysci/{$this->artist->slug}");

    // @todo $this->artist->refresh();
    $this->artist = $this->artist->fresh();

    expect($this->artist->photo)->toBeNull();
});

test('photo can be uploaded', function () {
    Storage::fake('testing');

    Queue::fake();

    asUser()
        ->put(
            "artysci/{$this->artist->slug}",
            array_merge($this->attributes, [
                'photo' => UploadedFile::fake()->image('test.jpg'),
                'photo_crop' => $this->rawCrop,
                'photo_source' => 'test source',
            ]),
        )->assertRedirect("artysci/{$this->artist->slug}");

    // @todo $this->artist->refresh();
    $this->artist = $this->artist->fresh();

    expect($this->artist->photo)->not->toBeNull()
        ->and($this->artist->photo->source)->toBe('test source')
        ->and((string) $this->artist->photo->crop)->toBe((string) ArtistPhotoCrop::fake());

    expect(Photo::disk()->files('photos/original'))->toHaveCount(1);

    Queue::assertPushedWithChain(
        GenerateArtistPhotoPlaceholders::class,
        [GenerateArtistPhotoVariants::class],
    );
});

test('photo can be downloaded from specified uri', function () {
    $photoResponse = Http::response(file_get_contents(fixture('Images/photo.jpg')), 200);

    Http::fake(['filmpolski.pl/*' => $photoResponse]);

    Storage::fake('testing');

    Queue::fake();

    asUser()
        ->put(
            "artysci/{$this->artist->slug}",
            array_merge($this->attributes, [
                'photo_uri' => $uri = 'https://filmpolski.pl/z1/31z/2431_3.jpg',
                'photo_crop' => $this->rawCrop,
                'photo_source' => 'test source',
            ]),
        )->assertRedirect("artysci/{$this->artist->slug}");

    Http::assertSent(fn ($request) => $request->url() === $uri);

    // @todo $this->artist->refresh();
    $this->artist = $this->artist->fresh();

    expect($this->artist->photo)->not->toBeNull()
        ->and($this->artist->photo->source)->toBe('test source')
        ->and((string) $this->artist->photo->crop)->toBe((string) ArtistPhotoCrop::fake());

    expect($files = Photo::disk()->files('photos/original'))
        ->toHaveCount(1);

    expect(Photo::disk()->get($files[0]))
        ->toBe(file_get_contents(fixture('Images/photo.jpg')));

    Queue::assertPushedWithChain(
        GenerateArtistPhotoPlaceholders::class,
        [GenerateArtistPhotoVariants::class],
    );
});

test('crop can be updated without changing photo', function () {
    Storage::fake('testing');

    Photo::disk()->put('photos/original/test.jpg', 'contents');

    $this->artist->photo()->associate(
        Photo::create([
            'filename' => 'test.jpg',
            'crop' => ArtistPhotoCrop::fake(),
        ]),
    )->save();

    // @todo $this->artist->refresh();
    $this->artist = $this->artist->fresh();

    $newCrop = $this->rawCrop;

    Arr::set($newCrop, 'face.size', '190');

    expect(Arr::get($newCrop, 'face.size'))->toBe('190');

    Queue::fake();

    asUser()
        ->put(
            "artysci/{$this->artist->slug}",
            array_merge($this->attributes, [
                'photo_crop' => $newCrop,
            ]),
        )->assertRedirect("artysci/{$this->artist->slug}");

    // @todo $this->artist->refresh();
    $this->artist = $this->artist->fresh();

    expect($this->artist->photo->crop->face->size)->toBe(190);

    Queue::assertPushedWithChain(
        GenerateArtistPhotoPlaceholders::class,
        [GenerateArtistPhotoVariants::class],
    );
});
