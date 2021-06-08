<?php

use App\Services\FilmPolski;
use Carbon\CarbonInterval;

test('alias is properly registered', function () {
    expect(app(FilmPolski::class))->toBe(app('filmPolski'));
});

it('can create artist url', function () {
    expect(app('filmPolski')->url('112891'))
        ->toBe('http://www.filmpolski.pl/fp/index.php?osoba=112891');
});

it('can get photos from filmpolski', function ($personId, $personSource, $galleryId, $gallerySource, $expectedOutput) {
    Http::fakeSequence()
        ->push($personSource)
        ->push($gallerySource);

    expect(app('filmPolski')->photos($personId))->toBe($expectedOutput);

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

    Cache::shouldReceive('remember')->once()
        ->with('filmpolski-11232-photos', CarbonInterval::class, Closure::class)
        ->andReturn($images);

    expect(app('filmPolski')->photos(11232))->toBe($images);

    Http::assertSentCount(0);
});

it('can flush cached data', function () {
    $filmPolski = app('filmPolski');

    expect($filmPolski->forget(11232))->toBeFalse();

    Http::fake();

    expect($filmPolski->photos(11232))->toBe([]);

    expect($filmPolski->forget(11232))->toBeTrue();
});
