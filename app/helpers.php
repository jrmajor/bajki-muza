<?php

namespace App;

use Psl\Filesystem;

/**
 * @param non-empty-string $path
 */
function append_to_file_name(string $path, string $suffix, string $glue = '_'): string
{
    $baseName = Filesystem\get_filename($path);
    $extension = Filesystem\get_extension($path);

    return "{$baseName}{$glue}{$suffix}.{$extension}";
}
