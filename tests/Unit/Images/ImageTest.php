<?php

namespace Tests\Unit\Images;

use App\Images\Exceptions\OriginalDoesNotExist;
use App\Images\ImageCast;
use Illuminate\Contracts\Database\Eloquent\Castable;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Storage;

it('can store new image', function () {
    Storage::fake('testing');

    $uploadedFile = UploadedFile::fake()->image('test.jpg');

    Bus::fake();

    $image = TestCover::store($uploadedFile, function () {});

    expect($image)->toBeInstanceOf(TestCover::class)
        ->and($image->filename())->toEndWith('.jpg');

    expect(Storage::cloud()->files('covers/original'))->toHaveCount(1);

    Bus::assertDispatched(ProcessTestCover::class);
});

it('throws error when reprocessing image without original', function () {
    Storage::fake('testing');

    (new TestCover('test.jpg'))->reprocess(function () {});
})->expectException(OriginalDoesNotExist::class);

it('deletes responsive variants when reprocessing responsive images', function () {
    Storage::fake('testing');

    Storage::cloud()->put('covers/original/test.jpg', 'contents');
    Storage::cloud()->put('covers/128/test.jpg', 'contents');

    Storage::cloud()->assertExists('covers/128/test.jpg');

    Bus::fake();

    (new TestCover('test.jpg'))->reprocess(function () {});

    Storage::cloud()->assertMissing('covers/128/fileWithMissingVariants.jpg');

    Bus::assertDispatched(ProcessTestCover::class);
});

it('can reprocess responsive images', function () {
    Storage::fake('testing');

    Storage::cloud()->put('covers/original/test.jpg', 'contents');

    Bus::fake();

    (new TestCover('test.jpg'))->reprocess(function () {});

    Bus::assertDispatched(ProcessTestCover::class);
});

it('can get its filename', function () {
    expect((new TestCover('testFilename.jpg'))->filename())
        ->toBe('testFilename.jpg');
});

it('can get original url', function () {
    expect(
        (new TestCoverWithMockedTemporaryUrl('testFilename.jpg'))->originalUrl(),
    )->toBe('testUrl');
});

it('can get url for given size', function () {
    Storage::fake('testing');

    expect((new TestCover('testFilename.jpg'))->url(128))
        ->toEndWith('/covers/128/testFilename.jpg');
});

it('can get check whether original is missing', function () {
    Storage::fake('testing');

    expect((new TestCover('fileWithoutOriginal.jpg'))->originalMissing())->toBeTrue();

    $uploadedFile = UploadedFile::fake()->image('test.jpg');

    $image = TestCover::store($uploadedFile, function () {});

    expect($image->originalMissing())->toBeFalse();
});

it('can get check whether responsive variant is missing', function () {
    Storage::fake('testing');

    expect(
        (new TestCover('fileWithout128Variant.jpg'))
            ->responsiveVariantMissing(128),
    )->toBeTrue();

    Storage::cloud()->put('covers/128/fileWith128Variant.jpg', 'contents');

    expect(
        (new TestCover('fileWith128Variant.jpg'))
            ->responsiveVariantMissing(128),
    )->toBeFalse();
});

it('can get check which responsive variants are missing', function () {
    Storage::fake('testing');

    Storage::cloud()->put('covers/128/fileWithMissingVariants.jpg', 'contents');

    expect(
        (new TestCover('fileWithMissingVariants.jpg'))
            ->missingResponsiveVariants(),
    )->toHaveCount(2)
    ->not->toContain(128)
    ->toContain(192)->toContain(256);
});

it('implements castable interface', function () {
    expect(new TestCover('test.jpg'))
        ->toBeInstanceOf(Castable::class);

    expect(TestCover::castUsing([]))
        ->toBeInstanceOf(ImageCast::class);
});
