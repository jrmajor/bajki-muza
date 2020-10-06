<?php

namespace Tests;

function asUser(?Authenticatable $user = null, string $driver = null): TestCase
{
    return test()->asUser($user, $driver);
}

function fixture(string $path = ''): string
{
    return __DIR__.'/Fixtures'.($path ? DIRECTORY_SEPARATOR.$path : $path);
};
