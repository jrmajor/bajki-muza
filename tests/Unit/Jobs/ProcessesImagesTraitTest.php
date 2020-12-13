<?php

use App\Jobs\ProcessesImages;
use Spatie\TemporaryDirectory\TemporaryDirectory;
use function Tests\fixture;

it('can copy image to temporary directory', function () {
    $temporaryDirectory = (new TemporaryDirectory)->create();

    $imagesProcessor = $this->getMockForTrait(ProcessesImages::class);

    $copiedFilePath = $imagesProcessor->copyToTemporaryDirectory(
        fopen(fixture('Images/cover.jpg'), 'r'),
        $temporaryDirectory, 'desiredFilename.jpg'
    );

    expect($copiedFilePath)->toBe($temporaryDirectory->path('desiredFilename.jpg'))
        ->and($copiedFilePath)->toEndWith('desiredFilename.jpg');

    expect(file_get_contents($temporaryDirectory->path('desiredFilename.jpg')))
        ->toBe(file_get_contents(fixture('Images/cover.jpg')));

    $temporaryDirectory->delete();
});

it('can generate placeholders cropped to square', function () {
    $temporaryDirectory = (new TemporaryDirectory)->create();

    $imagesProcessor = $this->getMockForTrait(ProcessesImages::class);

    $tinyJpg = $imagesProcessor->generateTinyJpg(
        fixture('Images/cover.jpg'), 'square', $temporaryDirectory
    );

    expect($tinyJpg)->toStartWith('data:image/svg+xml;base64,');

    $temporaryDirectory->delete();
});

it('can generate placeholder preserving aspect ratio', function () {
    $temporaryDirectory = (new TemporaryDirectory)->create();

    $imagesProcessor = $this->getMockForTrait(ProcessesImages::class);

    $tinyJpg = $imagesProcessor->generateTinyJpg(
        fixture('Images/photo.jpg'), 'height', $temporaryDirectory
    );

    expect($tinyJpg)->toStartWith('data:image/svg+xml;base64,');

    $temporaryDirectory->delete();
});

it('can generate responsive images cropped to square', function () {
    $temporaryDirectory = (new TemporaryDirectory)->create();

    $imagesProcessor = $this->getMockForTrait(ProcessesImages::class);

    $responsiveImagePath = $imagesProcessor->generateResponsiveImage(
        fixture('Images/cover.jpg'),
        128, 'square',
        $temporaryDirectory
    );

    expect($responsiveImagePath)->toBe($temporaryDirectory->path('cover_128.jpg'));

    $this->assertFileExists($responsiveImagePath);

    $temporaryDirectory->delete();
});

it('can generate responsive images preserving aspect ratio', function () {
    $temporaryDirectory = (new TemporaryDirectory)->create();

    $imagesProcessor = $this->getMockForTrait(ProcessesImages::class);

    $responsiveImagePath = $imagesProcessor->generateResponsiveImage(
        fixture('Images/photo.jpg'),
        112, 'height',
        $temporaryDirectory
    );

    expect($responsiveImagePath)->toBe($temporaryDirectory->path('photo_112.jpg'));

    $this->assertFileExists($responsiveImagePath);

    $temporaryDirectory->delete();
});

test('appendToFilename method works', function () {
    $imagesProcessor = $this->getMockForTrait(ProcessesImages::class);

    expect($imagesProcessor->appendToFileName('/var/folders/0k/T/desiredFilename.jpg', '_tiny'))
        ->toBe('desiredFilename_tiny.jpg');

    expect($imagesProcessor->appendToFileName('test.jpeg', '.temp'))->toBe('test.temp.jpeg');
});
