<?php

use App\Jobs\ProcessesImages;
use Illuminate\Support\Str;
use Spatie\TemporaryDirectory\TemporaryDirectory;

it('can copy image to temporary directory', function () {
    $temporaryDirectory = (new TemporaryDirectory)->create();

    $imagesProcessor = $this->getMockForTrait(ProcessesImages::class);

    $copiedFilePath = $imagesProcessor->copyToTemporaryDirectory(
        fopen(__DIR__.'/cover.jpg', 'r'),
        $temporaryDirectory, 'desiredFilename.jpg'
    );

    expect($copiedFilePath)->toBe($temporaryDirectory->path('desiredFilename.jpg'))
        ->and(Str::endsWith($copiedFilePath, 'desiredFilename.jpg'))->toBeTrue();

    expect(file_get_contents($temporaryDirectory->path('desiredFilename.jpg')))
        ->toBe(file_get_contents(__DIR__.'/cover.jpg'));

    $temporaryDirectory->delete();
});

it('generates placeholder', function () {
    $temporaryDirectory = (new TemporaryDirectory)->create();

    $imagesProcessor = $this->getMockForTrait(ProcessesImages::class);

    expect($imagesProcessor->generateTinyJpg(__DIR__.'/cover.jpg', $temporaryDirectory))
        ->toBe(file_get_contents(__DIR__.'/placeholder.b64'));

    $temporaryDirectory->delete();
});

it('can generate responsive images', function () {
    $temporaryDirectory = (new TemporaryDirectory)->create();

    $imagesProcessor = $this->getMockForTrait(ProcessesImages::class);

    $responsiveImagePath = $imagesProcessor->generateResponsiveImage(
        __DIR__.'/cover.jpg',
        128, 'fit',
        $temporaryDirectory
    );

    expect($responsiveImagePath)->toBe($temporaryDirectory->path('cover_128.jpg'));

    expect(file_get_contents($responsiveImagePath))
        ->toBe(file_get_contents(__DIR__.'/cover_128.jpg'));

    $temporaryDirectory->delete();
});

test('appendToFilename method works', function () {
    $imagesProcessor = $this->getMockForTrait(ProcessesImages::class);

    expect($imagesProcessor->appendToFileName('/var/folders/0k/T/desiredFilename.jpg', '_tiny'))
        ->toBe('desiredFilename_tiny.jpg');

    expect($imagesProcessor->appendToFileName('test.jpeg', '.temp'))->toBe('test.temp.jpeg');
});
