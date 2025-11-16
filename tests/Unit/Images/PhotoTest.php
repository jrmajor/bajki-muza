<?php

namespace Tests\Unit\Images;

use App\Images\Jobs\ProcessImage;
use App\Images\Photo;
use App\Images\Values\ArtistFaceCrop;
use App\Images\Values\ArtistImageCrop;
use App\Images\Values\ArtistPhotoCrop;
use App\Models\Artist;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Storage;
use PHPUnit\Framework\Attributes\TestDox;
use Tests\TestCase;

final class PhotoTest extends TestCase
{
    #[TestDox('it can get list of variants')]
    public function testVariants(): void
    {
        $this->assertSame(['default', 'face'], Photo::variants());
    }

    #[TestDox('it casts dimensions to integers')]
    public function testDimensionsCast(): void
    {
        $this->assertSame(2137, new Photo(['width' => '2137'])->width);
        $this->assertSame(2115, new Photo(['height' => '2115'])->height);
    }

    #[TestDox('it casts crop to ArtistPhotoCrop')]
    public function testCropCast(): void
    {
        $photo = Photo::create([
            'filename' => 'test.jpg',
            'crop' => ArtistPhotoCrop::fake(),
        ])->refresh();

        $this->assertInstanceOf(ArtistPhotoCrop::class, $photo->crop);
        $this->assertSame(
            ArtistPhotoCrop::fake()->toArray(),
            $photo->crop->toArray(),
        );
    }

    #[TestDox('it stores new photo in correct path and dispatches necessary jobs to process it')]
    public function testStore(): void
    {
        Storage::fake('testing');

        Queue::fake();

        $image = Photo::store(
            UploadedFile::fake()->image('test.png'),
            ['crop' => ArtistPhotoCrop::fake()],
        );

        $this->assertStringEndsWith('.png', $image->filename());
        $this->assertSame(
            ArtistPhotoCrop::fake()->toArray(),
            $image->crop()->toArray(),
        );

        $this->assertCount(1, Photo::disk()->files('photos/original'));

        Queue::assertPushed(ProcessImage::class);
    }

    #[TestDox('it returns correct original path')]
    public function testOriginalPath(): void
    {
        $this->assertSame(
            'photos/original/test.jpg',
            new Photo(['filename' => 'test.jpg'])->originalPath(),
        );
    }

    #[TestDox('it returns correct path for default variant')]
    public function testDefaultVariantPath(): void
    {
        $this->assertSame(
            'photos/default/test.jpg',
            new Photo(['filename' => 'test.jpg'])->variantPath('default'),
        );
    }

    #[TestDox('it returns correct path for face variant')]
    public function testFaceVariantPath(): void
    {
        $this->assertSame(
            'photos/face/test.jpg',
            new Photo(['filename' => 'test.jpg'])->variantPath('face'),
        );
    }

    #[TestDox('it reprocesses image when crop is updated')]
    public function testCropUpdated(): void
    {
        Storage::fake('testing');

        Queue::fake();

        $photo = Photo::store(
            UploadedFile::fake()->image('test.jpg'),
            ['crop' => $crop = ArtistPhotoCrop::fake()],
        );

        $this->assertSame($crop->toArray(), $photo->crop()->toArray());

        $newCrop = new ArtistPhotoCrop(
            new ArtistFaceCrop(x: 181, y: 246, size: 190), // size was 189
            new ArtistImageCrop(x: 79, y: 247, width: 529, height: 352),
        );

        $photo->update(['crop' => $newCrop]);

        $this->assertSame($newCrop->toArray(), $photo->refresh()->crop()->toArray());

        Queue::assertPushed(ProcessImage::class);
    }

    #[TestDox('it can get its artists')]
    public function testArtists(): void
    {
        $photo = Photo::create([
            'filename' => 'tXySLaaEbhfyzLXm6QggZY5VSFulyN2xLp4OgYSy.png',
            'crop' => ArtistPhotoCrop::fake(),
        ]);

        $artists = Artist::factory(2)->photo($photo)->create();

        $photo->refresh();

        $this->assertCount(2, $photo->artists);
        $this->assertSameModel($artists[0], $photo->artists[0]);
        $this->assertSameModel($artists[1], $photo->artists[1]);
    }
}
