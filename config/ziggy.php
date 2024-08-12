<?php

return [

    'output' => [
        'path' => 'resources/js/helpers/ziggy.js',
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
