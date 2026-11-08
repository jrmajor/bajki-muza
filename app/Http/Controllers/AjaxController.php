<?php

namespace App\Http\Controllers;

use App\Models\Artist;
use App\Services\Discogs;
use App\Services\FilmPolski;
use App\Services\Wikipedia;
use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Attributes\Controllers\Middleware;

#[Middleware(static function (Request $request, Closure $next) {
    return blank(self::search($request))
        ? response()->json([])
        : $next($request);
})]
class AjaxController extends Controller
{
    public function artists(Request $request): JsonResponse
    {
        $artists = Artist::query()
            ->where('name', 'like', '%' . self::search($request) . '%')
            ->orderBy('name')
            ->take(10)
            ->get()
            ->map->name;

        return response()->json($artists);
    }

    public function discogs(Discogs $discogs, Request $request): JsonResponse
    {
        return response()->json(
            $discogs->search(self::search($request)),
        );
    }

    public function filmPolski(FilmPolski $filmPolski, Request $request): JsonResponse
    {
        return response()->json(
            $filmPolski->search(self::search($request)),
        );
    }

    public function wikipedia(Wikipedia $wikipedia, Request $request): JsonResponse
    {
        return response()->json(
            $wikipedia->search(self::search($request)),
        );
    }

    private static function search(Request $request): string
    {
        return $request->string('search', strict: true)->value();
    }
}
