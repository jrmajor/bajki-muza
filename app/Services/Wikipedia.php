<?php

namespace App\Services;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;

class Wikipedia
{
    public static function extract(string $title): ?string
    {
        $response = Http::get('https://pl.wikipedia.org/w/api.php', [
            'action' => 'query',
            'titles' => $title,
            'prop' => 'extracts',
            'exintro' => 1,
            'redirects' => 1,
            'format' => 'json',
        ]);

        return Arr::first($response['query']['pages'])['extract'] ?? null;
    }
}
