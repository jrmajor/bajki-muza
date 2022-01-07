<?php

use Psl\Filesystem;

return [

    'paths' => [
        resource_path('views'),
    ],

    'compiled' => env(
        'VIEW_COMPILED_PATH',
        Filesystem\canonicalize(storage_path('framework/views')),
    ),

];
