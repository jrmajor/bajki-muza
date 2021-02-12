<?php

namespace App\Http\Controllers;

use App\Models\Artist;
use Facades\App\Services\Discogs;
use Facades\App\Services\FilmPolski;
use Facades\App\Services\Wikipedia;
use Illuminate\Http\Request;

class AjaxController extends Controller
{
    public function __construct()
    {
        $this->middleware(function (Request $request, $next) {
            return blank($request->input('search'))
                ? response()->json([])
                : $next($request);
        });
    }

    public function artists(Request $request)
    {
        $artists = Artist::where('name', 'like', '%'.$request->input('search').'%')
            ->orderBy('name')
            ->take(10)
            ->get()
            ->map->name;

        return response()->json($artists);
    }

    public function discogs(Request $request)
    {
        return response()->json(
            Discogs::search($request->input('search'))->toArray(),
        );
    }

    public function filmPolski(Request $request)
    {
        return response()->json(
            FilmPolski::search($request->input('search'))->toArray(),
        );
    }

    public function wikipedia(Request $request)
    {
        return response()->json(
            Wikipedia::search($request->input('search'))->toArray(),
        );
    }
}
