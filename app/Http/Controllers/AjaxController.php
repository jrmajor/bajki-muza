<?php

namespace App\Http\Controllers;

use App\Models\Artist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use InvalidArgumentException;
use Symfony\Component\DomCrawler\Crawler;

class AjaxController extends Controller
{
    public function artists(Request $request)
    {
        return response()->json(
            Artist::where('name', 'like', '%'.$request->input('search').'%')
                ->orderBy('name')
                ->take(10)
                ->get()
                ->map(fn ($artist) => $artist->name)
        );
    }

    public function discogs(Request $request)
    {
        $search = Http::withHeaders([
            'Authorization' => 'Discogs token='.config('services.discogs.token'),
        ])->get('https://api.discogs.com/database/search', [
            'query' => $request->input('search'),
            'type' => 'artist',
        ])->json();

        return response()->json(
            collect($search['results'])
                ->take(10)
                ->map(fn ($artist) => ['id' => $artist['id'], 'name' => $artist['title']])
        );
    }

    public function filmPolski(Request $request)
    {
        $source = Http::get('http://www.filmpolski.pl/fp/index.php', [
            'szukaj' => $request->input('search'),
        ])->body();

        try {
            $crawler = (new Crawler($source))
                ->filter('.wynikiszukania');

            if ($crawler->count() === 0) {
                return response()->json([]);
            }

            $crawler = $crawler->first()->children();

            $max = $crawler->count() <= 10 ? $crawler->count() : 10;

            $people = collect();

            for ($i = 0; $i < $max; $i++) {
                $people->push([
                    'id' => Str::afterLast($crawler->eq($i)->children()->filter('a')->last()->attr('href'), '/'),
                    'name' => $crawler->eq($i)->children()->filter('a')->last()->text(),
                ]);
            }

            return response()->json($people->unique('id'));
        } catch (InvalidArgumentException $e) {
            return response()->json([]);
        }
    }

    public function wikipedia(Request $request)
    {
        $search = Http::get('https://pl.wikipedia.org/w/api.php', [
            'action' => 'opensearch',
            'search' => $request->input('search'),
            'limit' => 10,
            'redirects' => 'resolve',
        ])->json();

        return response()->json(
            collect($search[1] ?? [])
                ->map(fn ($title) => ['id' => str_replace(' ', '_', $title), 'name' => $title])
        );
    }
}
