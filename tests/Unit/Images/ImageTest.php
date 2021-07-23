<?php

use App\Images\Exceptions\OriginalDoesNotExist;
use Carbon\Carbon;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Storage;
use Tests\Unit\Images\ProcessTestCover;
use Tests\Unit\Images\TestCover;

it('can store new image', function () {
    Storage::fake('testing');

    $uploadedFile = UploadedFile::fake()->image('test.jpg');

    Bus::fake();

    $image = TestCover::store($uploadedFile);

    expect($image)
        ->toBeInstanceOf(TestCover::class)
        ->filename()->toEndWith('.jpg');

    expect(TestCover::disk()->files('covers/original'))->toHaveCount(1);

    Bus::assertDispatched(ProcessTestCover::class);
});

it('throws error when reprocessing image without original', function () {
    Storage::fake('testing');

    (new TestCover(['filename' => 'test.jpg']))->reprocess();
})->expectException(OriginalDoesNotExist::class);

it('deletes responsive variants when reprocessing responsive images', function () {
    Storage::fake('testing');

    TestCover::disk()->put('covers/original/test.jpg', 'contents');
    TestCover::disk()->put('covers/128/test.jpg', 'contents');

    TestCover::disk()->assertExists('covers/128/test.jpg');

    Bus::fake();

    (new TestCover(['filename' => 'test.jpg']))->reprocess();

    TestCover::disk()->assertMissing('covers/128/fileWithMissingVariants.jpg');

    Bus::assertDispatched(ProcessTestCover::class);
});

it('can reprocess responsive images', function () {
    Storage::fake('testing');

    TestCover::disk()->put('covers/original/test.jpg', 'contents');

    Bus::fake();

    (new TestCover(['filename' => 'test.jpg']))->reprocess();

    Bus::assertDispatched(ProcessTestCover::class);
});

it('can get its filename', function () {
    expect(
        (new TestCover(['filename' => 'testFilename.jpg']))->filename(),
    )->toBe('testFilename.jpg');
});

it('can get original url', function () {
    $cover = new class([
        'filename' => 'testFilename.jpg',
    ]) extends TestCover {
        public static function disk(): FilesystemAdapter
        {
            return Mockery::mock(FilesystemAdapter::class)
                ->shouldReceive('temporaryUrl')
                ->with('covers/original/testFilename.jpg', Carbon::class)
                ->andReturn('testUrl')
                ->mock();
        }
    };

    expect($cover->originalUrl())->toBe('testUrl');
});

it('can get url for given size', function () {
    Storage::fake('testing');

    expect(
        (new TestCover(['filename' => 'testFilename.jpg']))->url(128),
    )->toEndWith('/covers/128/testFilename.jpg');
});

it('can get its placeholder')
    ->expect(
        (new TestCover(['placeholder' => 'test placeholder']))->placeholder(),
    )->toBe('test placeholder');

it('can get check whether original is missing', function () {
    Storage::fake('testing');

    expect(
        (new TestCover(['filename' => 'fileWithoutOriginal.jpg']))->originalMissing(),
    )->toBeTrue();

    $uploadedFile = UploadedFile::fake()->image('test.jpg');

    $image = TestCover::store($uploadedFile);

    expect($image->originalMissing())->toBeFalse();
});

it('can get check whether responsive variant is missing', function () {
    Storage::fake('testing');

    expect(
        (new TestCover(['filename' => 'fileWithout128Variant.jpg']))
            ->responsiveVariantMissing(128),
    )->toBeTrue();

    TestCover::disk()->put('covers/128/fileWith128Variant.jpg', 'contents');

    expect(
        (new TestCover(['filename' => 'fileWith128Variant.jpg']))
            ->responsiveVariantMissing(128),
    )->toBeFalse();
});

it('can get check which responsive variants are missing', function () {
    Storage::fake('testing');

    TestCover::disk()->put('covers/128/fileWithMissingVariants.jpg', 'contents');

    expect(
        (new TestCover(['filename' => 'fileWithMissingVariants.jpg']))
            ->missingResponsiveVariants()->all(),
    )->toBe([192, 256]);
});
