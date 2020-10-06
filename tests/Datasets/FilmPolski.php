<?php

use function Tests\fixture;

dataset('filmpolski', function () {
    // with photo and gallery
    yield [
        '11232', file_get_contents(fixture('FilmPolski/11232/osoba.html')),
        '14232', file_get_contents(fixture('FilmPolski/11232/galeria_osoby.html')),
        require fixture('FilmPolski/11232/photos.php'),
    ];

    // with photo and gallery
    yield [
        '111707', file_get_contents(fixture('FilmPolski/111707/osoba.html')),
        '141707', file_get_contents(fixture('FilmPolski/111707/galeria_osoby.html')),
        require fixture('FilmPolski/111707/photos.php'),
    ];

    // with no photo and no gallery
    yield [
        '11178011', file_get_contents(fixture('FilmPolski/11178011/osoba.html')),
        null, null, [],
    ];

    // with photo and no gallery
    yield [
        '116397', file_get_contents(fixture('FilmPolski/116397/osoba.html')),
        null, null,
        require fixture('FilmPolski/116397/photos.php'),
    ];

    // with no photo and gallery
    yield [
        '11148285', file_get_contents(fixture('FilmPolski/11148285/osoba.html')),
        '14148285', file_get_contents(fixture('FilmPolski/11148285/galeria_osoby.html')),
        require fixture('FilmPolski/11148285/photos.php'),
    ];
});
