<?php

namespace App\Services;

use App\Services\Concerns\HandlesTransientFailures;
use App\Values\Wikipedia\Artist;
use Carbon\CarbonInterval;
use Closure;
use Illuminate\Container\Attributes\Config;
use Illuminate\Http\Client\PendingRequest;
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
    use HandlesTransientFailures;

    private const string Endpoint = 'https://pl.wikipedia.org/w/api.php';

    public function __construct(
        #[Config('services.user_agent')] protected string $userAgent,
    ) { }

    protected function request(): PendingRequest
    {
        return $this->retryTransientFailures(Http::withUserAgent($this->userAgent));
    }

    /**
     * @return list<Artist>
     */
    public function search(string $search): array
    {
        $response = $this->request()->get(self::Endpoint, [
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
        return rescue(fn () => $this->remember($title, fn () => $this->fetchExtract($title)));
    }

    protected function fetchExtract(string $title): ?string
    {
        $response = $this->request()->get(self::Endpoint, [
            'action' => 'query',
            'titles' => $title,
            'prop' => 'extracts',
            'exintro' => 1,
            'redirects' => 1,
            'format' => 'json',
        ])->throwIf($this->isTransientFailure(...));

        /** @phpstan-ignore argument.templateType */
        $extract = Iter\first($response['query']['pages'])['extract'] ?? null;

        return $extract === null ? null : $extract |> Html\strip_tags(...) |> Str\trim(...);
    }

    /**
     * @param Closure(): ?string $callback
     */
    protected function remember(string $title, Closure $callback): ?string
    {
        $titleHash = Hash\hash($title, Hash\Algorithm::Md5);

        return Cache::flexible(
            "wikipedia-{$titleHash}-extract",
            [CarbonInterval::week(), CarbonInterval::year()],
            $callback,
            ['seconds' => 600],
        );
    }

    public function refreshCache(string $title): void
    {
        // fetch before forgetting, so a failed request doesn't wipe the cache
        $extract = $this->fetchExtract($title);

        $this->forget($title);

        $this->remember($title, fn () => $extract);
    }

    public function forget(string $title): bool
    {
        $titleHash = Hash\hash($title, Hash\Algorithm::Md5);

        return Cache::forget("wikipedia-{$titleHash}-extract");
    }
}
