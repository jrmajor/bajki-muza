<?php

namespace Database\Factories;

use App\Models\Artist;
use App\Models\Tale;
use App\Values\CreditType;
use Illuminate\Database\Eloquent\Factories\Factory;

class TaleFactory extends Factory
{
    protected $model = Tale::class;

    public function definition()
    {
        return [
            'title' => $this->faker->sentence,
            'year' => $this->faker->numberBetween(1950, 1970),
            'nr' => $this->faker->numberBetween(1, 250),
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function (Tale $tale) {
            $tale->credits()->attach(
                Artist::factory()->create()->id,
                ['type' => CreditType::directing(), 'nr' => 0]
            );

            for ($i = 1; $i <= 2; $i++) {
                $tale->credits()->attach(
                    Artist::factory()->create()->id,
                    ['type' => CreditType::text(), 'nr' => $i]
                );
            }

            for ($i = 1; $i <= 2; $i++) {
                $tale->credits()->attach(
                    Artist::factory()->create()->id,
                    ['type' => CreditType::music(), 'nr' => $i]
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
                    Artist::factory()->create()->id,
                    [
                        'credit_nr'  => $credit_nr,
                        'characters' => $characters,
                    ]
                );
            }
        });
    }

    public function withoutRelations()
    {
        return $this->afterCreating(function (Tale $tale) {
            $tale->credits()->detach();
            $tale->actors()->detach();
        });
    }
}
