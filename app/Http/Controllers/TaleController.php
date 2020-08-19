<?php

namespace App\Http\Controllers;

use App\Artist;
use App\Http\Requests\StoreTale;
use App\Tale;
use Illuminate\Support\Str;

class TaleController extends Controller
{
    public function index()
    {
        return view('tales.index', [
            'tales' => Tale::orderBy('year')
                            ->orderBy('title')
                            ->paginate(25),
        ]);
    }

    public function create()
    {
        return view('tales.create');
    }

    public function store(StoreTale $request)
    {
        $tale = new Tale();

        $tale->fill($request->validated());

        if ($request->input('director')) {
            $tale->director_id = Artist::findBySlugOrNew($request->input('director'))->id;
        }

        $tale->save();

        $lyricists = [];
        foreach ($request->validated()['lyricists'] ?? [] as $lyricist) {
            $lyricists[Artist::findBySlugOrNew($lyricist['artist'])->id] = [
                'credit_nr' => $lyricist['credit_nr'],
            ];
        }
        $tale->lyricists()->sync($lyricists);

        $composers = [];
        foreach ($request->validated()['composers'] ?? [] as $composer) {
            $composers[Artist::findBySlugOrNew($composer['artist'])->id] = [
                'credit_nr' => $composer['credit_nr'],
            ];
        }
        $tale->composers()->sync($composers);

        $actors = [];
        foreach ($request->input('actors') ?? [] as $actor) {
            $actors[Artist::findBySlugOrNew($actor['artist'])->id] = [
                'credit_nr' => $actor['credit_nr'],
                'characters' => $actor['characters'],
            ];
        }
        $tale->actors()->sync($actors);

        return redirect()->route('tales.show', $tale);
    }

    public function show(Tale $tale)
    {
        return view('tales.show', ['tale' => $tale]);
    }

    public function edit(Tale $tale)
    {
        return view('tales.edit', ['tale' => $tale]);
    }

    public function update(StoreTale $request, Tale $tale)
    {
        $tale->fill($request->validated());

        if ($request->input('director')) {
            $tale->director_id = Artist::findBySlugOrNew($request->input('director'))->id;
        } else {
            $tale->director_id = null;
        }

        $tale->save();

        $lyricists = [];
        foreach ($request->validated()['lyricists'] ?? [] as $lyricist) {
            $lyricists[Artist::findBySlugOrNew($lyricist['artist'])->id] = [
                'credit_nr' => $lyricist['credit_nr'],
            ];
        }
        $tale->lyricists()->sync($lyricists);

        $composers = [];
        foreach ($request->validated()['composers'] ?? [] as $composer) {
            $composers[Artist::findBySlugOrNew($composer['artist'])->id] = [
                'credit_nr' => $composer['credit_nr'],
            ];
        }
        $tale->composers()->sync($composers);

        $actors = [];
        foreach ($request->input('actors') ?? [] as $actor) {
            $actors[Artist::findBySlugOrNew($actor['artist'])->id] = [
                'credit_nr' => $actor['credit_nr'],
                'characters' => $actor['characters'],
            ];
        }
        $tale->actors()->sync($actors);

        return redirect()->route('tales.show', $tale);
    }

    public function destroy(Tale $tale)
    {
        //
    }
}
