<?php

namespace Database\Factories;

use App\Images\Photo;
use App\Images\Values\ArtistPhotoCrop;
use App\Models\Artist;
use Illuminate\Database\Eloquent\Factories\Attributes\UseModel;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Artist>
 */
#[UseModel(Artist::class)]
class ArtistFactory extends Factory
{
    public function definition(): array
    {
        $name = $this->faker->name();

        return [
            'name' => $name,
            'discogs' => $this->faker->randomNumber(6),
            'filmpolski' => $this->faker->randomNumber(5),
            'wikipedia' => str_replace(' ', '_', $name),
        ];
    }

    public function noPhoto(): static
    {
        return $this->state(['photo_filename' => null]);
    }

    public function photo(
        string|Photo|null $photo = null,
        ?ArtistPhotoCrop $crop = null,
    ): static {
        if (! $photo instanceof Photo) {
            $photo = Photo::create([
                'filename' => $photo ?? 'test.jpg',
                'crop' => $crop ?? ArtistPhotoCrop::fake(),
            ]);
        }

        return $this->state(['photo_filename' => $photo->filename()]);
    }
}
