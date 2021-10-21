<?php

return [

    'paths' => [
        resource_path('views'),
    ],

    'compiled' => env(
        'VIEW_COMPILED_PATH',
        Safe\realpath(storage_path('framework/views')),
    ),

];
