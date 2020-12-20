<?php

namespace Tests;

use App\Models\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    public function asUser(?User $user = null, string $driver = null): TestCase
    {
        return $this->actingAs($user ?? User::factory()->create(), $driver);
    }
}
