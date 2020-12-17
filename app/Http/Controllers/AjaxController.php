<?php

namespace App\Http\Controllers;

use App\Models\Artist;
use Facades\App\Services\Discogs;
use Facades\App\Services\FilmPolski;
use Facades\App\Services\Wikipedia;
use Illuminate\Http\Request;

class AjaxController extends Controller
{
    public function artists(Request $request)
    {
        if (blank($request->input('search'))) {
            return response()->json([]);
        }

        $artists = Artist::where('name', 'like', '%'.$request->input('search').'%')
            ->orderBy('name')
            ->take(10)
            ->get()
            ->map->name;

        return response()->json($artists);
    }

    public function discogs(Request $request)
    {
        if (blank($request->input('search'))) {
            return response()->json([]);
        }

        return response()->json(
            Discogs::search($request->input('search'))->toArray(),
        );
    }

    public function filmPolski(Request $request)
    {
        if (blank($request->input('search'))) {
            return response()->json([]);
        }

        return response()->json(
            FilmPolski::search($request->input('search'))->toArray(),
        );
    }

    public function wikipedia(Request $request)
    {
        if (blank($request->input('search'))) {
            return response()->json([]);
        }

        return response()->json(
            Wikipedia::search($request->input('search'))->toArray(),
        );
    }
}
