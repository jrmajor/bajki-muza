<?php

namespace App\Http\Controllers;

use App\Models\Artist;
use App\Models\Tale;
use Illuminate\Http\Request;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;
use Symfony\Component\HttpFoundation\Response;

class SitemapController extends Controller
{
    public function __invoke(Request $request): Response
    {
        $sitemap = Sitemap::create()
            ->add(Url::create('/artysci')->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY))
            ->add(Url::create('/bajki')->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY));

        Artist::lazy(100)->each(fn (Artist $artist) => $sitemap->add(
            Url::create(route('artists.show', $artist))
                ->setLastModificationDate($artist->updated_at)
                ->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY),
        ));

        Tale::lazy(100)->each(fn (Tale $tale) => $sitemap->add(
            Url::create(route('tales.show', $tale))
                ->setLastModificationDate($tale->updated_at)
                ->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY),
        ));

        return $sitemap->toResponse($request);
    }
}
