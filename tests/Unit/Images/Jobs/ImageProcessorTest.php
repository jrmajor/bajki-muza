<?php

namespace Tests\Unit\Images\Jobs;

use App\Images\Jobs\ImageProcessor;
use App\Images\Values\FitMethod;
use Exception;
use PHPUnit\Framework\Attributes\TestDox;
use Psl\Filesystem;
use ReflectionClass;
use Tests;
use Tests\TestCase;

final class ImageProcessorTest extends TestCase
{
    #[TestDox('it can be constructed using resource')]
    public function testFromResource(): void
    {
        $originalPath = Tests\fixture('Images/cover.jpg');
        /** @phpstan-ignore-next-line */
        $processor = new ImageProcessor($r = fopen($originalPath, 'r'), 'jpg');

        /** @phpstan-ignore-next-line */
        fclose($r);

        $path = (new ReflectionClass(ImageProcessor::class))
            ->getProperty('path')
            ->getValue($processor);

        $this->assertNotSame($originalPath, $path);
        $this->assertFileEquals($originalPath, $path);

        unset($processor);

        $this->assertFileDoesNotExist($path);
    }

    #[TestDox('it can be constructed using path')]
    public function testFromPath(): void
    {
        $originalPath = Tests\fixture('Images/cover.jpg');
        $processor = ImageProcessor::fromPath($originalPath);

        $path = (new ReflectionClass(ImageProcessor::class))
            ->getProperty('path')
            ->getValue($processor);

        $this->assertNotSame($originalPath, $path);
        $this->assertFileEquals($originalPath, $path);

        unset($processor);

        $this->assertFileDoesNotExist($path);
    }

    #[TestDox('it fails when path does not have extension info')]
    public function testFromPathNoExtension(): void
    {
        $this->expectException(Exception::class);

        $processor = ImageProcessor::fromPath(__DIR__ . '/no-extension');
    }

    #[TestDox('it can get image dimensions')]
    public function testDimensions(): void
    {
        $processor = ImageProcessor::fromPath(Tests\fixture('Images/photo.jpg'));

        $this->assertSame(['width' => 640, 'height' => 964], $processor->dimensions());
    }

    #[TestDox('it can generate placeholders cropped to square')]
    public function testSquarePlaceholder(): void
    {
        $processor = ImageProcessor::fromPath(Tests\fixture('Images/cover.jpg'));

        $tinyJpg = $processor->generateTinyJpg(FitMethod::Square);

        $this->assertStringStartsWith('data:image/svg+xml;base64,', $tinyJpg);
    }

    #[TestDox('it can generate placeholder preserving aspect ratio')]
    public function testPlaceholder(): void
    {
        $processor = ImageProcessor::fromPath(Tests\fixture('Images/photo.jpg'));

        $tinyJpg = $processor->generateTinyJpg(FitMethod::Height);

        $this->assertStringStartsWith('data:image/svg+xml;base64,', $tinyJpg);
    }

    #[TestDox('it can generate responsive images cropped to square')]
    public function testSquareResponsive(): void
    {
        $processor = ImageProcessor::fromPath(Tests\fixture('Images/cover.jpg'));

        $responsiveImage = $processor->responsiveImage(28, FitMethod::Square);

        $this->assertFileExists($responsiveImage);

        Filesystem\delete_file($responsiveImage);
    }

    #[TestDox('it can generate responsive images preserving aspect ratio')]
    public function testResponsive(): void
    {
        $processor = ImageProcessor::fromPath(Tests\fixture('Images/photo.jpg'));

        $responsiveImage = $processor->responsiveImage(112, FitMethod::Height);

        $this->assertFileExists($responsiveImage);

        Filesystem\delete_file($responsiveImage);
    }
}
