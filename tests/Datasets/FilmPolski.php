<?php

use function Tests\fixture;
use function Tests\read_fixture;

dataset('filmpolski', function () {
    // with photo and gallery
    yield 'Bogusz Bilewski' => [
        '11232', read_fixture('FilmPolski/11232/osoba.html'),
        '14232', read_fixture('FilmPolski/11232/galeria_osoby.html'),
        require fixture('FilmPolski/11232/photos.php'),
    ];

    // with photo and gallery
    yield 'Kalina Jędrusik' => [
        '111707', read_fixture('FilmPolski/111707/osoba.html'),
        '141707', read_fixture('FilmPolski/111707/galeria_osoby.html'),
        require fixture('FilmPolski/111707/photos.php'),
    ];

    // with no photo and no gallery
    yield 'Kalina Cyz' => [
        '11178011', read_fixture('FilmPolski/11178011/osoba.html'),
        null, null, [],
    ];

    // with photo and no gallery
    yield 'Maria Gierszanin' => [
        '116397', read_fixture('FilmPolski/116397/osoba.html'),
        null, null,
        require fixture('FilmPolski/116397/photos.php'),
    ];

    // with no photo and gallery
    yield 'Kamil Maria Banasiak' => [
        '11148285', read_fixture('FilmPolski/11148285/osoba.html'),
        '14148285', read_fixture('FilmPolski/11148285/galeria_osoby.html'),
        require fixture('FilmPolski/11148285/photos.php'),
    ];
});
