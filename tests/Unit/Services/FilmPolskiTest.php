<?php

use Facades\App\Services\FilmPolski;

it('can create artist url', function () {
    expect(FilmPolski::url('112891'))
        ->toBe('http://www.filmpolski.pl/fp/index.php?osoba=112891');
});
