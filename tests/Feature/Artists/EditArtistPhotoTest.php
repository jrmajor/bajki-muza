<?php

use App\Jobs\ProcessArtistPhoto;
use App\Models\Artist;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use function Pest\Laravel\put;
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

    $this->crop = [
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

    $this->artist = Artist::factory()->create($this->attributes);
});

test('photo can be deleted', function () {
    $this->artist->forceFill([
        'photo' => 'test.jpg',
        'photo_source' => 'test',
        'photo_width' => 21,
        'photo_height' => 37,
        'photo_crop' => '{}',
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

    Bus::fake();

    asUser()
        ->put(
            "artysci/{$this->artist->slug}",
            array_merge($this->attributes, [
                'photo' => UploadedFile::fake()->image('test.jpg'),
                'photo_crop' => $this->crop,
            ])
        )
        ->assertRedirect("artysci/{$this->artist->slug}");

    expect(Storage::cloud()->files('photos/original'))->toHaveCount(1);

    Bus::assertDispatched(ProcessArtistPhoto::class, function ($command) {
        expect($this->artist->is($command->artist))->toBeTrue();
        expect($command->crop)->toBe($this->crop);
        return true;
    });
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
                'photo_crop' => $this->crop,
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
        expect($command->crop)->toBe($this->crop);
        return true;
    });
});

test('crop can be updated without changing photo', function () {
    Bus::fake();

    $this->artist->setAttribute('photo', 'test.jpg')->save();

    asUser()
        ->put(
            "artysci/{$this->artist->slug}",
            array_merge($this->attributes, [
                'photo_crop' => $this->crop,
            ])
        )
        ->assertRedirect("artysci/{$this->artist->slug}");

    Bus::assertDispatched(ProcessArtistPhoto::class, function ($command) {
        expect($this->artist->is($command->artist))->toBeTrue();
        expect($command->filename)->toBe('test.jpg');
        expect($command->crop)->toBe($this->crop);
        return true;
    });
});
