<?php

namespace App\Services;

use Carbon\CarbonInterval;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class Discogs
{
    public function __construct(string $token)
    {
        $this->token = $token;
    }

    protected function request(): PendingRequest
    {
        return Http::withHeaders([
            'Authorization' => "Discogs token={$this->token}",
        ]);
    }

    public function photos(int $id): array
    {
        return Cache::remember(
            "discogs-$id-photos",
            CarbonInterval::week(),
            function () use ($id) {
                $artist = $this->request()
                    ->get("https://api.discogs.com/artists/$id")->json();

                return $artist['images'] ?? [];
            }
        );
    }

    public function forget(int $id): bool
    {
        return Cache::forget("discogs-$id-photos");
    }
}
