<?php

namespace Tests;

use App\Models\User;
use Psl\File;
use Psl\Str;

function asUser(?User $user = null, ?string $driver = null): TestCase
{
    return test()->asUser($user, $driver);
}

/**
 * @return non-empty-string
 */
function fixture(string $path = ''): string
{
    /** @var non-empty-string */
    return Str\strip_suffix(__DIR__ . "/Fixtures/{$path}", '/');
}

function read_fixture(string $path): string
{
    return File\read(fixture($path));
}
