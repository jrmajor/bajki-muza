<?php

use Carbon\CarbonInterval;
use Facades\App\Services\FilmPolski;

it('can create artist url', function () {
    expect(FilmPolski::url('112891'))
        ->toBe('http://www.filmpolski.pl/fp/index.php?osoba=112891');
});

it('can get photos from filmpolski', function ($personId, $personSource, $galleryId, $gallerySource, $expectedOutput) {
    Http::fakeSequence()
        ->push($personSource, 200)
        ->push($gallerySource, 200);

    expect(FilmPolski::photos($personId))->toBe($expectedOutput);

    Http::assertSentCount($galleryId ? 2 : 1);
})->with('filmpolski');

it('caches filmpolski photos', function () {
    $images = [
        'main' => [
            'year' => null,
            'photos' => ['test'],
        ],
    ];

    Http::fake();

    Cache::shouldReceive('remember')
        ->once()
        ->with(
            'filmpolski-11232-photos',
            CarbonInterval::class,
            Closure::class,
        )->andReturn($images);

    expect(FilmPolski::photos(11232))->toBe($images);

    Http::assertSentCount(0);
});

it('can flush cached data', function () {
    expect(FilmPolski::forget(11232))->toBeFalse();

    Http::fake();

    expect(FilmPolski::photos(11232))->toBe([]);

    expect(FilmPolski::forget(11232))->toBeTrue();
});
