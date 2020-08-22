<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Artist;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

$factory->define(Artist::class, function (Faker $faker) {
    $name = $faker->name;

    return [
        'name' => $name,
        'discogs' => $faker->randomNumber(6),
        'filmpolski' => $faker->randomNumber(5),
        'wikipedia' => str_replace(' ', '_', $name),
    ];
});
