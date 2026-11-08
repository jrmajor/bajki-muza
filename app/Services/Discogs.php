<?php

namespace App\Services;

use App\Services\Concerns\HandlesTransientFailures;
use App\Values\Discogs\Artist;
use App\Values\Discogs\DiscogsPhoto;
use App\Values\Discogs\DiscogsPhotos;
use Carbon\CarbonInterval;
use Closure;
use Illuminate\Container\Attributes\Config;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Psl\Vec;

class Discogs
{
    use HandlesTransientFailures;

    public function __construct(
        #[Config('services.discogs.token')] protected string $token,
    ) { }

    protected function request(): PendingRequest
    {
        return $this->retryTransientFailures(Http::withHeaders([
            'Authorization' => "Discogs token={$this->token}",
        ]));
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
            $response = $this->remember($id, fn () => $this->fetchArtist($id));

            return Vec\map($response['images'] ?? [], fn (array $p) => new DiscogsPhoto(
                $p['type'] === 'primary', $p['uri'], $p['uri150'], $p['width'], $p['height'],
            ));
        }, []);

        return new DiscogsPhotos($photos);
    }

    /**
     * @return array<string, mixed>
     */
    protected function fetchArtist(int $id): array
    {
        return $this->request()
            ->get("https://api.discogs.com/artists/{$id}")
            ->throwIf($this->isTransientFailure(...))
            ->json() ?? [];
    }

    /**
     * @param Closure(): array<string, mixed> $callback
     *
     * @return array<string, mixed>
     */
    protected function remember(int $id, Closure $callback): array
    {
        return Cache::flexible(
            "discogs-{$id}-photos",
            [CarbonInterval::week(), CarbonInterval::year()],
            $callback,
            ['seconds' => 600],
        );
    }

    public function refreshCache(int $id): void
    {
        // fetch before forgetting, so a failed request doesn't wipe the cache
        $artist = $this->fetchArtist($id);

        $this->forget($id);

        $this->remember($id, fn () => $artist);
    }

    public function forget(int $id): bool
    {
        return Cache::forget("discogs-{$id}-photos");
    }
}
