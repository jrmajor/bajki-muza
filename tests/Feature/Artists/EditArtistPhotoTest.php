<?php

namespace Tests\Feature\Artists;

use App\Images\Jobs\GenerateArtistPhotoPlaceholders;
use App\Images\Jobs\GenerateArtistPhotoVariants;
use App\Images\Photo;
use App\Images\Values\ArtistPhotoCrop;
use App\Models\Artist;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Storage;
use PHPUnit\Framework\Attributes\TestDox;
use Tests;
use Tests\TestCase;

final class EditArtistPhotoTest extends TestCase
{
    private array $attributes;

    private Artist $artist;

    protected function setUp(): void
    {
        parent::setUp();

        $this->attributes = [
            'slug' => 'ilona-kusmierska',
            'name' => 'Ilona Kuśmierska',
            'discogs' => 602488,
            'filmpolski' => 11623,
            'wikipedia' => 'Ilona_Kuśmierska',
        ];

        $this->artist = Artist::factory()
            ->noPhoto()->createOne($this->attributes);
    }

    #[TestDox('photo can be deleted')]
    public function testPhotoCanBeDeleted(): void
    {
        $this->artist->photo()->associate(
            Photo::create([
                'filename' => 'test.jpg',
                'crop' => ArtistPhotoCrop::fake(),
            ]),
        )->save();

        $this->artist->refresh();

        $this->assertNotNull($this->artist->photo);

        $this->asUser()->put(
            "artysci/{$this->artist->slug}",
            [...$this->attributes, 'remove_photo' => true],
        )->assertRedirect("artysci/{$this->artist->slug}");

        $this->artist->refresh();

        $this->assertNull($this->artist->photo);
    }

    #[TestDox('photo can be uploaded')]
    public function testPhotoCanBeUploaded(): void
    {
        Storage::fake('testing');

        Queue::fake();

        $this->asUser()->put("artysci/{$this->artist->slug}", [
            ...$this->attributes,
            'photo' => UploadedFile::fake()->image('test.jpg'),
            'photo_crop' => ArtistPhotoCrop::fake()->toArray(),
            'photo_source' => 'test source',
        ])->assertRedirect("artysci/{$this->artist->slug}");

        $photo = $this->artist->refresh()->photo;
        $this->assertNotNull($photo);
        $this->assertSame('test source', $photo->source);
        $this->assertSame((string) ArtistPhotoCrop::fake(), (string) $photo->crop);

        $this->assertCount(1, Photo::disk()->files('photos/original'));

        Queue::assertPushedWithChain(
            GenerateArtistPhotoPlaceholders::class,
            [GenerateArtistPhotoVariants::class],
        );
    }

    #[TestDox('photo can be updated using specified uri')]
    public function testPhotoCanBeUpdatedUsingUri(): void
    {
        Http::fake(['filmpolski.pl/*' => Http::response(Tests\read_fixture('Images/photo.jpg'))]);

        Storage::fake('testing');

        Queue::fake();

        $this->asUser()->put("artysci/{$this->artist->slug}", [
            ...$this->attributes,
            'photo_uri' => $uri = 'https://filmpolski.pl/z1/31z/2431_3.jpg',
            'photo_crop' => ArtistPhotoCrop::fake()->toArray(),
            'photo_source' => 'test source',
        ])->assertRedirect("artysci/{$this->artist->slug}");

        Http::assertSent(fn ($request) => $request->url() === $uri);

        $photo = $this->artist->refresh()->photo;
        $this->assertNotNull($photo);
        $this->assertSame('test source', $photo->source);
        $this->assertSame((string) ArtistPhotoCrop::fake(), (string) $photo->crop);

        $this->assertCount(1, $files = Photo::disk()->files('photos/original'));

        $this->assertStringEqualsFile(
            Tests\fixture('Images/photo.jpg'),
            Photo::disk()->get($files[0]),
        );

        Queue::assertPushedWithChain(
            GenerateArtistPhotoPlaceholders::class,
            [GenerateArtistPhotoVariants::class],
        );
    }

    #[TestDox('crop can be updated without changing photo')]
    public function testCropCanBeUpdated(): void
    {
        Storage::fake('testing');

        Photo::disk()->put('photos/original/test.jpg', 'contents');

        $this->artist->photo()->associate(
            Photo::create([
                'filename' => 'test.jpg',
                'crop' => ArtistPhotoCrop::fake(),
            ]),
        )->save();

        $this->assertNotNull($this->artist->refresh()->photo);

        $newCrop = ArtistPhotoCrop::fake()->toArray();
        $newCrop['face']['size'] = '190';

        Queue::fake();

        $this->asUser()->put(
            "artysci/{$this->artist->slug}",
            [...$this->attributes, 'photo_crop' => $newCrop],
        )->assertRedirect("artysci/{$this->artist->slug}");

        $this->assertSame(190, $this->artist->refresh()->photo->crop->face->size);

        Queue::assertPushedWithChain(
            GenerateArtistPhotoPlaceholders::class,
            [GenerateArtistPhotoVariants::class],
        );
    }
}
