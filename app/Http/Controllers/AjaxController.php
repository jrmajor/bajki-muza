<?php

namespace App\Http\Controllers;

use App\Models\Artist;
use App\Services\Discogs;
use App\Services\FilmPolski;
use App\Services\Wikipedia;
use Closure;
use Illuminate\Http\Request;

class AjaxController extends Controller
{
    public function __construct(
        protected Discogs $discogs,
        protected FilmPolski $filmPolski,
        protected Wikipedia $wikipedia,
    ) {
        $this->middleware(function (Request $request, Closure $next) {
            return blank($request->input('search'))
                ? response()->json([])
                : $next($request);
        });
    }

    public function artists(Request $request)
    {
        $artists = Artist::query()
            ->where('name', 'like', '%' . $request->input('search') . '%')
            ->orderBy('name')
            ->take(10)
            ->get()
            ->map->name;

        return response()->json($artists);
    }

    public function discogs(Request $request)
    {
        return response()->json(
            $this->discogs->search($request->input('search')),
        );
    }

    public function filmPolski(Request $request)
    {
        return response()->json(
            $this->filmPolski->search($request->input('search')),
        );
    }

    public function wikipedia(Request $request)
    {
        return response()->json(
            $this->wikipedia->search($request->input('search')),
        );
    }
}
