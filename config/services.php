<?php

return [

    'user_agent' => env('USER_AGENT', 'BajkiMuza/dev'),

    'discogs' => [
        'token' => env('DISCOGS_TOKEN'),
    ],

    'posthog' => [
        'token' => env('POSTHOG_TOKEN'),
    ],

    'fathom' => [
        'id' => env('FATHOM_SITE_ID'),
    ],

];
