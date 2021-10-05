<?php

namespace Tests;

use App\Models\User;

function asUser(?User $user = null, ?string $driver = null): TestCase
{
    return test()->asUser($user, $driver);
}

function fixture(string $path = ''): string
{
    return __DIR__ . '/Fixtures' . ($path ? '/' . $path : $path);
}
