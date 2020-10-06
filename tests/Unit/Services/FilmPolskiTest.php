<?php

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
