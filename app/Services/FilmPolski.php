<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use InvalidArgumentException;
use Symfony\Component\DomCrawler\Crawler;

class FilmPolski
{
    public function url(int $id): string
    {
        return "http://www.filmpolski.pl/fp/index.php?osoba=$id";
    }

    public function photos(int $id): array
    {
        $photos = [];

        $source = $this->getPersonSource($id);

        $mainPhoto = $this->getMainPhoto($source);

        if ($mainPhoto !== null) {
            $photos = [
                'main' => [
                    'year' => null,
                    'photos' => [$mainPhoto],
                ],
            ];
        }

        $galleryId = $this->getGalleryId($source);

        if ($galleryId === null) {
            return $photos;
        }

        $gallerySource = $this->getGallerySource($galleryId);

        return array_merge($photos, $this->getPhotosFromGallery($gallerySource));
    }

    protected function getPersonSource(int $id): string
    {
        return Http::get('http://www.filmpolski.pl/fp/index.php', [
            'osoba' => $id,
        ])->body();
    }

    protected function getGallerySource(int $galleryId): string
    {
        return Http::get('http://www.filmpolski.pl/fp/index.php', [
            'galeria_osoby' => $galleryId,
        ])->body();
    }

    protected function getMainPhoto(string $source): ?string
    {
        try {
            $crawler = (new Crawler($source))
                ->filter('.zdjecie')
                ->filter('img');

            if ($crawler->count() === 0) {
                return null;
            }

            return $crawler->attr('src');
        } catch (InvalidArgumentException $e) {
            return null;
        }
    }

    protected function getGalleryId(string $source): ?int
    {
        try {
            $crawler = (new Crawler($source))
                ->filter('.galeria_mala')
                ->children()->last();

            return (int) Str::after($crawler->attr('href'), '/');
        } catch (InvalidArgumentException $e) {
            return null;
        }
    }

    protected function getPhotosFromGallery(string $gallerySource): array
    {
        $photos = [];

        try {
            $crawler = (new Crawler($gallerySource))
                ->filter('#galeria_osoby')
                ->children();

            $count = $crawler->count();
        } catch (InvalidArgumentException $e) {
            return [];
        }

        for ($i = 0; $i < $count; $i++) {
            try {
                $nodeCrawler = $crawler->eq($i);

                if ($nodeCrawler->nodeName() === 'h2') {
                    $title = $nodeCrawler->text();
                    continue;
                }

                if ($nodeCrawler->attr('class') === 'opzdj') {
                    $photos[$title]['year'] = $nodeCrawler->text();
                    continue;
                }

                if ($nodeCrawler->attr('class') !== 'galeria_osoby_zdjecie') {
                    continue;
                }

                $photo = $nodeCrawler
                    ->children()->children()
                    ->attr('src');

                $photos[$title]['photos'][] = $photo;
            } catch (InvalidArgumentException $e) {
                continue;
            }
        }

        return $photos;
    }
}
