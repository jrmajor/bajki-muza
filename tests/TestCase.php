<?php

namespace Tests;

use App\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    public function asUser(?Authenticatable $user = null, string $driver = null): TestCase
    {
        return $this->actingAs($user ?? User::factory()->create(), $driver);
    }

}
