<?php

namespace Tests\Unit\Images;

use App\Images\Exceptions\OriginalDoesNotExist;
use Carbon\Carbon;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Storage;
use Mockery;
use Mockery\MockInterface;
use PHPUnit\Framework\Attributes\TestDox;
use Tests\TestCase;

final class ImageTest extends TestCase
{
    #[TestDox('it can store new image')]
    public function testStore(): void
    {
        Storage::fake('testing');

        $uploadedFile = UploadedFile::fake()->image('test.jpg');

        Bus::fake();

        $image = TestCover::store($uploadedFile);

        $this->assertStringEndsWith('.jpg', $image->filename());

        $this->assertCount(1, TestCover::disk()->files('covers/original'));

        Bus::assertDispatched(ProcessTestCover::class);
    }

    #[TestDox('it throws error when reprocessing image without original')]
    public function testReprocessNoOriginal(): void
    {
        Storage::fake('testing');

        $this->expectException(OriginalDoesNotExist::class);

        (new TestCover(['filename' => 'test.jpg']))->reprocess();
    }

    #[TestDox('it deletes variants when reprocessing images')]
    public function testReprocessDeletesVariants(): void
    {
        Storage::fake('testing');

        TestCover::disk()->put('covers/original/test.jpg', 'contents');
        TestCover::disk()->put($vartiantPath = 'covers/default/test.jpg', 'contents');

        TestCover::disk()->assertExists($vartiantPath);

        Bus::fake();

        (new TestCover(['filename' => 'test.jpg']))->reprocess();

        TestCover::disk()->assertMissing($vartiantPath);

        Bus::assertDispatched(ProcessTestCover::class);
    }

    #[TestDox('it can reprocess variants')]
    public function testReprocess(): void
    {
        Storage::fake('testing');

        TestCover::disk()->put('covers/original/test.jpg', 'contents');

        Bus::fake();

        (new TestCover(['filename' => 'test.jpg']))->reprocess();

        Bus::assertDispatched(ProcessTestCover::class);
    }

    #[TestDox('it can get its filename')]
    public function testFilename(): void
    {
        $this->assertSame(
            'testFilename.jpg',
            (new TestCover(['filename' => 'testFilename.jpg']))->filename(),
        );
    }

    #[TestDox('it can get its extension')]
    public function testExtension(): void
    {
        $this->assertSame(
            'png',
            (new TestCover(['filename' => 'testFilename.png']))->extension(),
        );
    }

    #[TestDox('it can get original url')]
    public function testOriginalUrl(): void
    {
        $cover = new class ([
            'filename' => 'testFilename.jpg',
        ]) extends TestCover {
            public static function disk(): FilesystemAdapter
            {
                return tap(
                    Mockery::mock(FilesystemAdapter::class),
                    fn (MockInterface $m) => $m
                        ->shouldReceive('temporaryUrl')
                        ->with('covers/original/testFilename.jpg', Carbon::class)
                        ->andReturn('testUrl'),
                );
            }
        };

        $this->assertSame('testUrl', $cover->originalUrl());
    }

    #[TestDox('it can get url for given variant')]
    public function testVariantUrl(): void
    {
        Storage::fake('testing');

        $this->assertStringEndsWith(
            '/covers/default/testFilename.jpg',
            (new TestCover(['filename' => 'testFilename.jpg']))->url(),
        );

        $this->assertStringEndsWith(
            '/covers/face/testFilename.jpg',
            (new TestCover(['filename' => 'testFilename.jpg']))->url('face'),
        );
    }

    #[TestDox('it can get its placeholder')]
    public function testPlaceholder(): void
    {
        $this->assertSame(
            'test placeholder',
            (new TestCover(['placeholder' => 'test placeholder']))->placeholder(),
        );
    }

    #[TestDox('it can get check whether original is missing')]
    public function testOriginalMissing(): void
    {
        Storage::fake('testing');

        $this->assertTrue(
            (new TestCover(['filename' => 'fileWithoutOriginal.jpg']))->originalMissing(),
        );

        $uploadedFile = UploadedFile::fake()->image('test.jpg');

        $image = TestCover::store($uploadedFile);

        $this->assertFalse($image->originalMissing());
    }

    #[TestDox('it can check whether a variant is missing')]
    public function testVariantMissing(): void
    {
        Storage::fake('testing');

        $this->assertTrue(
            (new TestCover(['filename' => 'fileWithoutDefaultVariant.jpg']))
                ->variantMissing('default'),
        );

        TestCover::disk()->put('covers/default/fileWithDefaultVariant.jpg', 'contents');

        $this->assertFalse(
            (new TestCover(['filename' => 'fileWithDefaultVariant.jpg']))
                ->variantMissing('default'),
        );
    }

    #[TestDox('it can check which variants are missing')]
    public function testVariantsMissing(): void
    {
        Storage::fake('testing');

        TestCover::disk()->put('covers/128/fileWithMissingVariants.jpg', 'contents');

        $this->assertSame(
            ['default'],
            (new TestCover(['filename' => 'fileWithMissingVariants.jpg']))
                ->missingVariants(),
        );
    }

    #[TestDox('it can read file stream')]
    public function testReadStream(): void
    {
        Storage::fake('testing');

        TestCover::disk()->put('covers/original/test.jpg', 'contents');

        $stream = (new TestCover(['filename' => 'test.jpg']))->readStream();

        $this->assertSame('contents', fread($stream, 16));

        fclose($stream);
    }
}
