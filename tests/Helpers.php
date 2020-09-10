<?php

namespace Tests;

function asUser(?Authenticatable $user = null, string $driver = null): TestCase
{
    return test()->asUser($user, $driver);
}
