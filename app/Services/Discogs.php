<?php

namespace App\Services;

use App\Values\Discogs\Artist;
use App\Values\Discogs\DiscogsPhoto;
use App\Values\Discogs\DiscogsPhotos;
use Carbon\CarbonInterval;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Psl\Dict;
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

    public function search(string $search): Collection
    {
        $response = $this->request()
            ->get('https://api.discogs.com/database/search', [
                'query' => $search,
                'type' => 'artist',
                'per_page' => 10,
            ])->json();

        return collect($response['results'] ?? [])->map(Artist::fromArray(...));
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

            $photos = Vec\map($response['images'] ?? [], fn (array $p) => new DiscogsPhoto(
                $p['type'] === 'primary',
                ...Dict\select_keys($p, ['uri', 'uri150', 'width', 'height']),
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
