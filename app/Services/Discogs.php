<?php

namespace App\Services;

use App\Values\Discogs\ArtistCollection;
use App\Values\Discogs\PhotoCollection;
use Carbon\CarbonInterval;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class Discogs
{
    public function __construct(
        protected string $token,
    ) { }

    protected function request(): PendingRequest
    {
        return Http::withHeaders([
            'Authorization' => "Discogs token={$this->token}",
        ]);
    }

    public function search(string $search): ArtistCollection
    {
        $response = $this->request()
            ->get('https://api.discogs.com/database/search', [
                'query' => $search,
                'type' => 'artist',
                'per_page' => 10,
            ])->json();

        return ArtistCollection::fromArray($response['results'] ?? []);
    }

    public function url(int $id): string
    {
        return "https://www.discogs.com/artist/{$id}";
    }

    public function releaseUrl(int $id): string
    {
        return "https://www.discogs.com/release/{$id}";
    }

    public function photos(int $id): PhotoCollection
    {
        $response = Cache::remember(
            "discogs-{$id}-photos",
            CarbonInterval::week(),
            fn () => $this->request()
                ->get("https://api.discogs.com/artists/{$id}")->json(),
        );

        return PhotoCollection::fromArray($response['images'] ?? []);
    }

    public function refreshCache(int $id): void
    {
        $this->forget($id);

        $this->photos($id);
    }

    public function forget(int $id): bool
    {
        return Cache::forget("discogs-{$id}-photos");
    }
}
