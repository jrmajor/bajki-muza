<?php

namespace Tests\Unit\Images\Jobs;

use App\Images\Jobs\ProcessesImages;
use PHPUnit\Framework\Attributes\TestDox;
use Spatie\TemporaryDirectory\TemporaryDirectory;
use Tests;
use Tests\TestCase;

final class ProcessesImagesTraitTest extends TestCase
{
    use ProcessesImages;

    protected function setUp(): void
    {
        parent::setUp();

        $this->temporaryDirectory = (new TemporaryDirectory())->create();
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        $this->temporaryDirectory->delete();
    }

    #[TestDox('can copy image to temporary directory')]
    public function testTemporaryDirectory(): void
    {
        $copiedFilePath = $this->copyToTemporaryDirectory(
            fopen(Tests\fixture('Images/cover.jpg'), 'r') ?: $this->fail(),
            'desiredFilename.jpg',
        );

        $path = $this->temporaryDirectory->path('desiredFilename.jpg');
        $this->assertSame($path, $copiedFilePath);
        $this->assertStringEndsWith('desiredFilename.jpg', $copiedFilePath);

        $this->assertFileEquals(
            Tests\fixture('Images/cover.jpg'),
            $copiedFilePath,
        );
    }

    #[TestDox('can generate placeholders cropped to square')]
    public function testSquarePlaceholder(): void
    {
        $tinyJpg = $this->generateTinyJpg(
            Tests\fixture('Images/cover.jpg'), 'square',
        );

        $this->assertStringStartsWith('data:image/svg+xml;base64,', $tinyJpg);
    }

    #[TestDox('can generate placeholder preserving aspect ratio')]
    public function testPlaceholder(): void
    {
        $tinyJpg = $this->generateTinyJpg(
            Tests\fixture('Images/photo.jpg'), 'height',
        );

        $this->assertStringStartsWith('data:image/svg+xml;base64,', $tinyJpg);
    }

    #[TestDox('can generate responsive images cropped to square')]
    public function testSquareResponsive(): void
    {
        $responsiveImagePath = $this->generateResponsiveImage(
            Tests\fixture('Images/cover.jpg'), 128, 'square',
        );

        $this->assertSame(
            $this->temporaryDirectory->path('cover_128.jpg'),
            $responsiveImagePath,
        );

        $this->assertFileExists($responsiveImagePath);
    }

    #[TestDox('can generate responsive images preserving aspect ratio')]
    public function testResponsive(): void
    {
        $responsiveImagePath = $this->generateResponsiveImage(
            Tests\fixture('Images/photo.jpg'), 112, 'height',
        );

        $this->assertSame(
            $this->temporaryDirectory->path('photo_112.jpg'),
            $responsiveImagePath,
        );

        $this->assertFileExists($responsiveImagePath);
    }

    #[TestDox('appendToFilename method works')]
    public function testAppendToFilename(): void
    {
        $this->assertSame(
            'desiredFilename_tiny.jpg',
            $this->appendToFileName('/var/folders/0k/T/desiredFilename.jpg', 'tiny'),
        );


        $this->assertSame(
            'test.temp.jpeg',
            $this->appendToFileName('test.jpeg', 'temp', '.'),
        );
    }
}
