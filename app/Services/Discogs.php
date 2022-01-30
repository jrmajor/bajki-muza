<?php

namespace App\Services;

use App\Values\Discogs\Artist;
use App\Values\Discogs\DiscogsPhoto;
use App\Values\Discogs\DiscogsPhotos;
use Carbon\CarbonInterval;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Psl\Vec;

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

    /**
     * @return list<Artist>
     */
    public function search(string $search): array
    {
        $response = $this->request()->get(
            'https://api.discogs.com/database/search',
            ['query' => $search, 'type' => 'artist', 'per_page' => 10],
        )->json();

        return Vec\map(
            $response['results'] ?? [],
            fn (array $a) => new Artist($a['id'], $a['title']),
        );
    }

    public function url(int $id): string
    {
        return "https://www.discogs.com/artist/{$id}";
    }

    public function releaseUrl(int $id): string
    {
        return "https://www.discogs.com/release/{$id}";
    }

    public function photos(int $id): DiscogsPhotos
    {
        $photos = rescue(function () use ($id): array {
            $response = Cache::remember(
                "discogs-{$id}-photos", CarbonInterval::week(),
                fn () => $this->request()->get("https://api.discogs.com/artists/{$id}")->json(),
            );

            return Vec\map($response['images'] ?? [], fn (array $p) => new DiscogsPhoto(
                $p['type'] === 'primary', $p['uri'], $p['uri150'], $p['width'], $p['height'],
            ));
        }, []);

        return new DiscogsPhotos($photos);
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
