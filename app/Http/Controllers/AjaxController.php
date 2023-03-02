<?php

namespace App\Http\Controllers;

use App\Models\Artist;
use App\Services\Discogs;
use App\Services\FilmPolski;
use App\Services\Wikipedia;
use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AjaxController extends Controller
{
    public function __construct()
    {
        $this->middleware(function (Request $request, Closure $next) {
            return blank($request->input('search'))
                ? response()->json([])
                : $next($request);
        });
    }

    public function artists(Request $request): JsonResponse
    {
        $artists = Artist::query()
            ->where('name', 'like', '%' . $request->input('search') . '%')
            ->orderBy('name')
            ->take(10)
            ->get()
            ->map->name;

        return response()->json($artists);
    }

    public function discogs(Discogs $discogs, Request $request): JsonResponse
    {
        return response()->json(
            $discogs->search($request->input('search')),
        );
    }

    public function filmPolski(FilmPolski $filmPolski, Request $request): JsonResponse
    {
        return response()->json(
            $filmPolski->search($request->input('search')),
        );
    }

    public function wikipedia(Wikipedia $wikipedia, Request $request): JsonResponse
    {
        return response()->json(
            $wikipedia->search($request->input('search')),
        );
    }
}
