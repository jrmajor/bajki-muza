<?php

namespace Tests;

use Psl\File;
use Psl\Str;

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
