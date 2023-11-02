<?php

namespace Tests\Unit\Images;

use App\Images\Jobs\GenerateArtistPhotoPlaceholders;
use App\Images\Jobs\GenerateArtistPhotoVariants;
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
    #[TestDox('it can get list of full image sizes')]
    public function testImageSizes(): void
    {
        $this->assertSame([160, 240, 320], Photo::imageSizes());
    }

    #[TestDox('it can get list of cropped to face image sizes')]
    public function testFaceSizes(): void
    {
        $this->assertSame([56, 84, 112], Photo::faceSizes());
    }

    #[TestDox('it can get list of sizes')]
    public function testSizes(): void
    {
        $this->assertSame([56, 84, 112, 160, 240, 320], Photo::sizes());
    }

    #[TestDox('it casts dimensions to integers')]
    public function testDimensionsCast(): void
    {
        $this->assertSame(2137, (new Photo(['width' => '2137']))->width);
        $this->assertSame(2115, (new Photo(['height' => '2115']))->height);
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

        Queue::assertPushedWithChain(
            GenerateArtistPhotoPlaceholders::class,
            [GenerateArtistPhotoVariants::class],
        );
    }

    #[TestDox('it returns correct original path')]
    public function testOriginalPath(): void
    {
        $this->assertSame(
            'photos/original/test.jpg',
            (new Photo(['filename' => 'test.jpg']))->originalPath(),
        );
    }

    #[TestDox('it returns correct path for given size')]
    public function testSizePath(): void
    {
        $this->assertSame(
            'photos/384/test.jpg',
            (new Photo(['filename' => 'test.jpg']))->path(384),
        );
    }

    #[TestDox('it can get face placeholder')]
    public function testFacePlaceholder(): void
    {
        $this->assertSame(
            'test placeholder',
            (new Photo(['face_placeholder' => 'test placeholder']))->facePlaceholder(),
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

        Queue::assertPushedWithChain(
            GenerateArtistPhotoPlaceholders::class,
            [GenerateArtistPhotoVariants::class],
        );
    }

    #[TestDox('it can calculate aspect ratio')]
    public function testAspectRatio(): void
    {
        $this->assertSame(
            1.6, (new Photo(['width' => 16, 'height' => 10]))->aspectRatio(),
        );
    }

    #[TestDox('aspect ratio is null when dimensions are not set')]
    public function testNullAspectRatio(): void
    {
        $this->assertNull(
            (new Photo(['width' => null, 'height' => null]))->aspectRatio(),
        );
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
