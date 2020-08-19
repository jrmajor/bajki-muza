<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Artist;
use App\Tale;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

$factory->define(Tale::class, function (Faker $faker) {
    return [
        'title' => $faker->sentence,
        'year' => $faker->numberBetween(1950, 1970),
        'director_id' => factory(Artist::class)->create()->id,
        'nr' => $faker->numberBetween(1, 250),
        'cover' => $faker->md5,
    ];
});

$factory->afterCreating(Tale::class, function (Tale $tale, Faker $faker) {
    for ($i = 1; $i <= $faker->numberBetween(1, 2); $i++) {
        $tale->lyricists()->attach(
            factory(Artist::class)->create()->id,
            ['credit_nr'  => $i]
        );
    }

    for ($i = 1; $i <= $faker->numberBetween(1, 2); $i++) {
        $tale->composers()->attach(
            factory(Artist::class)->create()->id,
            ['credit_nr'  => $i]
        );
    }

    foreach ([
        'Jacek',
        'Placek',
        'Matka; Kobieta u ognia',
        'Sąsiadka I',
        'Sąsiadka II',
        'Osioł',
        'Pszczoła',
        'Bóbr',
        'Pelikan',
        'Złoty Pan',
        'Nieborak',
        'Herszt',
    ] as $credit_nr => $characters) {
        $tale->actors()->attach(
            factory(Artist::class)->create()->id,
            [
                'credit_nr'  => $credit_nr,
                'characters' => $characters,
            ]
        );
    }
});

$factory->state(Tale::class, 'withoutRelations', function () {
    return [];
});

$factory->afterCreatingState(Tale::class, 'withoutRelations', function (Tale $tale, Faker $faker) {
    $tale->lyricists()->detach();
    $tale->composers()->detach();
    $tale->actors()->detach();
});
