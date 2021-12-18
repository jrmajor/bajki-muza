<?php

namespace Database\Factories;

use App\Images\Cover;
use App\Models\Artist;
use App\Models\Tale;
use App\Values\CreditType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Tale>
 */
class TaleFactory extends Factory
{
    protected $model = Tale::class;

    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(),
            'year' => $this->faker->numberBetween(1950, 1970),
            'nr' => $this->faker->numberBetween(1, 250),
        ];
    }

    public function configure(): static
    {
        return $this->afterCreating(function (Tale $tale) {
            $tale->credits()->attach(
                Artist::factory()->createOne()->id,
                ['type' => CreditType::Directing, 'nr' => 0],
            );

            for ($i = 1; $i <= 2; $i++) {
                $tale->credits()->attach(
                    Artist::factory()->createOne()->id,
                    ['type' => CreditType::Text, 'nr' => $i],
                );
            }

            for ($i = 1; $i <= 2; $i++) {
                $tale->credits()->attach(
                    Artist::factory()->createOne()->id,
                    ['type' => CreditType::Music, 'nr' => $i],
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
                    Artist::factory()->createOne()->id,
                    [
                        'characters' => $characters,
                        'credit_nr' => $credit_nr,
                    ],
                );
            }
        });
    }

    public function noCover(): static
    {
        return $this->state(['cover_filename' => null]);
    }

    public function cover(string|Cover $cover = null): static
    {
        if (! $cover instanceof Cover) {
            $cover = Cover::create(['filename' => $cover ?? 'test.jpg']);
        }

        return $this->state(['cover_filename' => $cover->filename()]);
    }

    public function withoutRelations(): static
    {
        return $this->afterCreating(function (Tale $tale) {
            $tale->credits()->detach();
            $tale->actors()->detach();
        });
    }
}
