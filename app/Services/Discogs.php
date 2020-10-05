<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class Discogs
{
    public static function photos(int $id): array
    {
        $artist = Http::withHeaders([
            'Authorization' => 'Discogs token='.config('services.discogs.token'),
        ])->get("https://api.discogs.com/artists/$id")->json();

        return $artist['images'] ?? [];
    }
}
