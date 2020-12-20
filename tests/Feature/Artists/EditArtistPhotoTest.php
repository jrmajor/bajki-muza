<?php

use App\Images\Jobs\GenerateArtistPhotoPlaceholders;
use App\Images\Jobs\GenerateArtistPhotoVariants;
use App\Images\Values\ArtistPhotoCrop;
use App\Models\Artist;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
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

    $this->rawCrop = [
        'face' => [
            'x' => '181',
            'y' => '246',
            'size' => '189',
        ],
        'image'=> [
            'x' => '79',
            'y' => '247',
            'width' => '529',
            'height' => '352',
        ],
    ];

    $this->crop = ArtistPhotoCrop::fromStrings($this->rawCrop);

    $this->artist = Artist::factory()->create($this->attributes);
});

test('photo can be deleted', function () {
    $this->artist->forceFill([
        'photo' => 'test.jpg',
        'photo_source' => 'test',
        'photo_width' => 21,
        'photo_height' => 37,
        'photo_crop' => $this->crop,
        'photo_face_placeholder' => 'test',
        'photo_placeholder' => 'test',
    ]);

    asUser()
        ->put(
            "artysci/{$this->artist->slug}",
            array_merge($this->attributes, [
                'remove_photo' => true,
            ])
        )
        ->assertRedirect("artysci/{$this->artist->slug}");

    $this->artist->refresh();

    expect($this->artist->photo)->toBeNull();
    expect($this->artist->photo_source)->toBeNull();
    expect($this->artist->photo_width)->toBeNull();
    expect($this->artist->photo_height)->toBeNull();
    expect($this->artist->photo_crop)->toBeNull();
    expect($this->artist->photo_face_placeholder)->toBeNull();
    expect($this->artist->photo_placeholder)->toBeNull();
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
            ])
        )
        ->assertRedirect("artysci/{$this->artist->slug}");

    expect(Storage::cloud()->files('photos/original'))->toHaveCount(1);

    Queue::assertPushedWithChain(
        GenerateArtistPhotoPlaceholders::class,
        [GenerateArtistPhotoVariants::class],
    );
});

test('photo can be downloaded from specified uri', function () {
    $photoResponse = Http::response(file_get_contents(fixture('Images/photo.jpg')), 200);

    Http::fake([
        'filmpolski.pl/*' => $photoResponse,
    ]);

    Storage::fake('testing');

    Bus::fake();

    asUser()
        ->put(
            "artysci/{$this->artist->slug}",
            array_merge($this->attributes, [
                'photo_uri' => $uri = 'https://filmpolski.pl/z1/31z/2431_3.jpg',
                'photo_crop' => $this->rawCrop,
            ])
        )
        ->assertRedirect("artysci/{$this->artist->slug}");

    Http::assertSent(fn ($request) => $request->url() === $uri);

    expect($files = Storage::cloud()->files('photos/original'))->toHaveCount(1);

    expect(Storage::cloud()->get($files[0]))
        ->toBe(file_get_contents(fixture('Images/photo.jpg')));

    Bus::assertDispatched(ProcessArtistPhoto::class, function ($command) use ($files) {
        expect($this->artist->is($command->artist))->toBeTrue();
        expect($command->filename)->toBe(Str::afterLast($files[0], '/'));
        expect($command->crop->toArray())->toBe($this->crop->toArray());

        return true;
    });
})->skip('wip');

test('crop can be updated without changing photo', function () {
    Storage::fake('testing');

    Storage::cloud()->put('photos/original/test.jpg', 'contents');

    Queue::fake();

    $this->artist->setAttribute('photo', 'test.jpg')->save();

    asUser()
        ->put(
            "artysci/{$this->artist->slug}",
            array_merge($this->attributes, [
                'photo_crop' => $this->rawCrop,
            ])
        )
        ->assertRedirect("artysci/{$this->artist->slug}");

    Queue::assertPushedWithChain(
        GenerateArtistPhotoPlaceholders::class,
        [GenerateArtistPhotoVariants::class],
    );
});
