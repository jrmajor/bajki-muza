<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Attributes\UseModel;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<User>
 */
#[UseModel(User::class)]
class UserFactory extends Factory
{
    public function definition(): array
    {
        return [
            'username' => $this->faker->unique()->userName(),
            'password' => 'password',
            'remember_token' => Str::random(10),
        ];
    }
}
