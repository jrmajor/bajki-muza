<?php

namespace App\Services;

use App\Values\Wikipedia\Artist;
use Carbon\CarbonInterval;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Psl\Hash;
use Psl\Html;
use Psl\Iter;
use Psl\Str;
use Psl\Type;
use Psl\Vec;

class Wikipedia
{
    protected string $endpoint = 'https://pl.wikipedia.org/w/api.php';

    /**
     * @return list<Artist>
     */
    public function search(string $search): array
    {
        $response = Http::get($this->endpoint, [
            'action' => 'opensearch',
            'search' => $search,
            'limit' => 10,
            'redirects' => 'resolve',
        ])->json();

        $names = Type\vec(Type\string())->coerce($response[1]);
        $urls = Type\vec(Type\string())->coerce($response[3]);

        $range = Vec\range(0, Iter\count($names) - 1);

        return Vec\map($range, fn (int $n) => new Artist(
            urldecode(Str\after_last($urls[$n], '/')),
            $names[$n],
        ));
    }

    public function url(string $title): string
    {
        return "https://pl.wikipedia.org/wiki/{$title}";
    }

    public function extract(string $title): ?string
    {
        $titleHash = Hash\hash($title, Hash\Algorithm::MD5);

        return Cache::remember(
            "wikipedia-{$titleHash}-extract",
            CarbonInterval::week(),
            function () use ($title): ?string {
                $response = Http::get($this->endpoint, [
                    'action' => 'query',
                    'titles' => $title,
                    'prop' => 'extracts',
                    'exintro' => 1,
                    'redirects' => 1,
                    'format' => 'json',
                ]);

                /** @phpstan-ignore argument.templateType */
                $extract = Iter\first($response['query']['pages'])['extract'] ?? null;

                return $extract === null ? null : Str\trim(Html\strip_tags($extract));
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
        $titleHash = Hash\hash($title, Hash\Algorithm::MD5);

        return Cache::forget("wikipedia-{$titleHash}-extract");
    }
}
