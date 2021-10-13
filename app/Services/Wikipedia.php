<?php

namespace App\Services;

use App\Values\Wikipedia\Artist;
use Carbon\CarbonInterval;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class Wikipedia
{
    protected string $endpoint = 'https://pl.wikipedia.org/w/api.php';

    public function search(string $search): Collection
    {
        $artists = Http::get($this->endpoint, [
            'action' => 'opensearch',
            'search' => $search,
            'limit' => 10,
            'redirects' => 'resolve',
        ])
            ->collect()
            ->only(1, 3)
            ->transpose()
            ->map->combineKeys(['name', 'id'])
            ->each(function ($artist) {
                $artist['id'] = urldecode(Str::afterLast($artist['id'], '/'));
            })
            ->toArray();

        return collect($artists)->mapInto(Artist::class);
    }

    public function url(string $title): string
    {
        return "https://pl.wikipedia.org/wiki/{$title}";
    }

    public function extract(string $title): ?string
    {
        $titleHash = md5($title);

        return Cache::remember(
            "wikipedia-{$titleHash}-extract",
            CarbonInterval::week(),
            function () use ($title) {
                $response = Http::get($this->endpoint, [
                    'action' => 'query',
                    'titles' => $title,
                    'prop' => 'extracts',
                    'exintro' => 1,
                    'redirects' => 1,
                    'format' => 'json',
                ]);

                $extract = Arr::first($response['query']['pages'])['extract'] ?? null;

                return $extract === null ? null : trim(strip_tags($extract));
            },
        );
    }

    public function refreshCache(string $title): void
    {
        $this->forget($title);

        $this->extract($title);
    }

    public function forget(string $title): bool
    {
        $titleHash = md5($title);

        return Cache::forget("wikipedia-{$titleHash}-extract");
    }
}
