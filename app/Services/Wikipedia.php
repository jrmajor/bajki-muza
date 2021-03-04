<?php

namespace App\Services;

use App\Values\Wikipedia\ArtistCollection;
use Carbon\CarbonInterval;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class Wikipedia
{
    protected string $endpoint = 'https://pl.wikipedia.org/w/api.php';

    public function search(string $search): ArtistCollection
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

        return ArtistCollection::fromArray($artists);
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

                return Arr::first($response['query']['pages'])['extract'] ?? null;
            },
        );
    }

    public function forget(string $title): bool
    {
        $titleHash = md5($title);

        return Cache::forget("wikipedia-{$titleHash}-extract");
    }
}
