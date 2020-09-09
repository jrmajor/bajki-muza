<?php

namespace Database\Factories;

use App\Artist;
use Illuminate\Database\Eloquent\Factories\Factory;

class ArtistFactory extends Factory
{
    protected $model = Artist::class;

    public function definition()
    {
        $name = $this->faker->name;

        return [
            'name' => $name,
            'discogs' => $this->faker->randomNumber(6),
            'filmpolski' => $this->faker->randomNumber(5),
            'wikipedia' => str_replace(' ', '_', $name),
        ];
    }
}
