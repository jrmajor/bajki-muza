<?php

return [

    'output' => [
        'path' => 'resources/js/types/ziggy.js',
    ],

    'skip-route-function' => true,

    'only' => [
        'ajax.*',
        'tales.*',
        'artists.*',
        'login',
        'logout',
    ],

];
