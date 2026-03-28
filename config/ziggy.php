<?php

return [
    'output' => [
        'path' => 'resources/js/ziggy/index.js',
        'types' => App\Support\ZiggyTypesOutput::class,
    ],
    'only' => [
        'ajax.*',
        'tales.*',
        'artists.*',
        'login',
        'logout',
    ],
];
