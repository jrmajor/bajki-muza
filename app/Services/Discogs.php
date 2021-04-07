<?php

namespace App\Services;

use App\Values\Discogs\Artist;
use App\Values\Discogs\Photo;
use Carbon\CarbonInterval;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Collection;
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

    public function search(string $search): Collection
    {
        $response = $this->request()
            ->get('https://api.discogs.com/database/search', [
                'query' => $search,
                'type' => 'artist',
                'per_page' => 10,
            ])->json();

        return collect($response['results'] ?? [])->map([Artist::class, 'fromArray']);
    }

    public function url(int $id): string
    {
        return "https://www.discogs.com/artist/{$id}";
    }

    public function releaseUrl(int $id): string
    {
        return "https://www.discogs.com/release/{$id}";
    }

    public function photos(int $id): Collection
    {
        $response = Cache::remember(
            "discogs-{$id}-photos",
            CarbonInterval::week(),
            fn () => $this->request()
                ->get("https://api.discogs.com/artists/{$id}")->json(),
        );

        return collect($response['images'] ?? [])->mapInto(Photo::class);
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
