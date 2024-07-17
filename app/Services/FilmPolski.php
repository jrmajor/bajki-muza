<?php

namespace App\Services;

use App\Values\FilmPolski\Artist;
use App\Values\FilmPolski\PhotoGroup;
use Carbon\CarbonInterval;
use GuzzleHttp\Psr7\Uri;
use GuzzleHttp\Psr7\UriResolver;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str as LStr;
use Psl\Dict;
use Psl\Regex;
use Psl\Str;
use Psl\Type;
use Psl\Vec;
use Symfony\Component\DomCrawler\Crawler;

class FilmPolski
{
    /**
     * @return list<Artist>
     */
    public function search(string $search): array
    {
        $source = Http::get(
            'https://www.filmpolski.pl/fp/index.php',
            ['szukaj' => $search],
        )->body();

        return rescue(function () use ($source) {
            $crawler = (new Crawler($source))->filter('.wynikiszukania');

            if ($crawler->count() === 0) {
                return [];
            }

            $crawler = $crawler->first()->children();

            $max = $crawler->count() <= 10 ? $crawler->count() : 10;

            $people = [];

            for ($i = 0; $i < $max; $i++) {
                $url = $crawler->eq($i)->children()->filter('a')->last()->attr('href');
                $name = $crawler->eq($i)->children()->filter('a')->last()->text();

                $people[] = new Artist(Type\int()->coerce(Str\after_last($url, '/')), $name);
            }

            return Dict\unique_by($people, fn (Artist $a): int => $a->id);
        }, []);
    }

    public function url(int $id): string
    {
        return "https://www.filmpolski.pl/fp/index.php?osoba={$id}";
    }

    /**
     * @return list<PhotoGroup>
     */
    public function photos(int $id): array
    {
        return Cache::remember(
            "filmpolski-{$id}-photos",
            CarbonInterval::week(),
            function () use ($id): array {
                $photos = [];

                $source = $this->getPersonSource($id);
                $mainPhoto = $this->getMainPhoto($source);

                if ($mainPhoto !== null) {
                    $photos[] = new PhotoGroup(null, null, [$mainPhoto]);
                }

                $galleryId = $this->getGalleryId($source);

                if ($galleryId === null) {
                    return $photos;
                }

                $gallerySource = $this->getGallerySource($galleryId);
                $gallery = $this->getPhotosFromGallery($gallerySource);

                return [...$photos, ...$gallery];
            },
        );
    }

    protected function getPersonSource(int $id): string
    {
        return Http::get(
            'https://www.filmpolski.pl/fp/index.php',
            ['osoba' => $id],
        )->body();
    }

    protected function getGallerySource(int $galleryId): string
    {
        return Http::get(
            'https://www.filmpolski.pl/fp/index.php',
            ['galeria_osoby' => $galleryId],
        )->body();
    }

    protected function getMainPhoto(string $source): ?string
    {
        return rescue(function () use ($source): ?string {
            $crawler = (new Crawler($source))
                ->filter('.zdjecie')
                ->filter('img');

            if ($crawler->count() === 0) {
                return null;
            }

            return $this->resolveUrl($crawler->attr('src'));
        });
    }

    protected function getGalleryId(string $source): ?int
    {
        return rescue(function () use ($source): ?int {
            $crawler = (new Crawler($source))->filter('.galeria_mala');

            if (! $crawler->count()) {
                return null;
            }

            $url = $crawler->children()->last()->attr('href');

            return Type\int()->coerce(Str\after($url, '/'));
        });
    }

    /**
     * @return list<PhotoGroup>
     */
    protected function getPhotosFromGallery(string $gallerySource): array
    {
        return rescue(function () use ($gallerySource): array {
            $crawler = (new Crawler($gallerySource))
                ->filter('#galeria_osoby')
                ->children();

            $photos = [];
            $count = $crawler->count();

            for ($i = 0; $i < $count; $i++) {
                $nodeCrawler = $crawler->eq($i);

                if ($nodeCrawler->nodeName() === 'h2') {
                    $title = $nodeCrawler->text();

                    continue;
                }

                if ($nodeCrawler->attr('class') === 'opzdj') {
                    $photos[$title ?? '?']['year'] = $nodeCrawler->text();

                    continue;
                }

                if ($nodeCrawler->attr('class') !== 'galeria_osoby_zdjecie') {
                    continue;
                }

                $photo = $nodeCrawler->children()->children()->attr('src');
                $photo = Regex\replace($photo, '/\\/([0-9]+)i\\//', '/$1z/');
                $photo = $this->resolveUrl($photo);

                $photos[$title ?? '?']['photos'][] = $photo;
            }

            return Vec\map_with_key($photos, fn ($title, $data) => new PhotoGroup(
                LStr::title($title),
                Type\nullable(Type\int())->coerce($data['year']),
                $data['photos'],
            ));
        }, []);
    }

    public function refreshCache(int $id): void
    {
        $this->forget($id);

        $this->photos($id);
    }

    public function forget(int $id): bool
    {
        return Cache::forget("filmpolski-{$id}-photos");
    }

    private function resolveUrl(string $url): string
    {
        return UriResolver::resolve(
            new Uri('https://www.filmpolski.pl/fp/index.php'),
            new Uri($url),
        );
    }
}
