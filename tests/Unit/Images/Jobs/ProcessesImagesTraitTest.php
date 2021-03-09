<?php

use function Tests\fixture;

it('can copy image to temporary directory')->imageProcessor(function () {
    $copiedFilePath = $this->copyToTemporaryDirectory(
        fopen(fixture('Images/cover.jpg'), 'r'), 'desiredFilename.jpg',
    );

    expect($copiedFilePath)
        ->toBe($this->temporaryDirectory->path('desiredFilename.jpg'))
        ->toEndWith('desiredFilename.jpg');

    expect(file_get_contents($copiedFilePath))
        ->toBe(file_get_contents(fixture('Images/cover.jpg')));
});

it('can generate placeholders cropped to square')->imageProcessor(function () {
    $tinyJpg = $this->generateTinyJpg(
        fixture('Images/cover.jpg'), 'square',
    );

    expect($tinyJpg)->toStartWith('data:image/svg+xml;base64,');
});

it('can generate placeholder preserving aspect ratio')->imageProcessor(function () {
    $tinyJpg = $this->generateTinyJpg(
        fixture('Images/photo.jpg'), 'height',
    );

    expect($tinyJpg)->toStartWith('data:image/svg+xml;base64,');
});

it('can generate responsive images cropped to square')->imageProcessor(function ($testCase) {
    $responsiveImagePath = $this->generateResponsiveImage(
        fixture('Images/cover.jpg'), 128, 'square',
    );

    expect($responsiveImagePath)
        ->toBe($this->temporaryDirectory->path('cover_128.jpg'));

    $testCase->assertFileExists($responsiveImagePath);
});

it('can generate responsive images preserving aspect ratio')->imageProcessor(function ($testCase) {
    $responsiveImagePath = $this->generateResponsiveImage(
        fixture('Images/photo.jpg'), 112, 'height',
    );

    expect($responsiveImagePath)
        ->toBe($this->temporaryDirectory->path('photo_112.jpg'));

    $testCase->assertFileExists($responsiveImagePath);
});

test('appendToFilename method works')->imageProcessor(function () {
    expect($this->appendToFileName('/var/folders/0k/T/desiredFilename.jpg', '_tiny'))
        ->toBe('desiredFilename_tiny.jpg');

    expect($this->appendToFileName('test.jpeg', '.temp'))
        ->toBe('test.temp.jpeg');
});
