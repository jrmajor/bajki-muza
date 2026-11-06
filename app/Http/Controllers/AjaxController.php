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
            return blank($this->search($request))
                ? response()->json([])
                : $next($request);
        });
    }

    public function artists(Request $request): JsonResponse
    {
        $artists = Artist::query()
            ->where('name', 'like', '%' . $this->search($request) . '%')
            ->orderBy('name')
            ->take(10)
            ->get()
            ->map->name;

        return response()->json($artists);
    }

    public function discogs(Discogs $discogs, Request $request): JsonResponse
    {
        return response()->json(
            $discogs->search($this->search($request)),
        );
    }

    public function filmPolski(FilmPolski $filmPolski, Request $request): JsonResponse
    {
        return response()->json(
            $filmPolski->search($this->search($request)),
        );
    }

    public function wikipedia(Wikipedia $wikipedia, Request $request): JsonResponse
    {
        return response()->json(
            $wikipedia->search($this->search($request)),
        );
    }

    private function search(Request $request): string
    {
        return $request->string('search', strict: true)->value();
    }
}
