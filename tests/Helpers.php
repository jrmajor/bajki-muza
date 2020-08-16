<?php

namespace Tests;

use Tests\TestCase;

function asUser(?Authenticatable $user = null, string $driver = null): TestCase
{
    return test()->asUser($user, $driver);
}
