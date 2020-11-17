<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTale;
use App\Jobs\ProcessTaleCover;
use App\Models\Artist;
use App\Models\Tale;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class TaleController extends Controller
{
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

        if ($request->file('cover')) {
            $path = Storage::cloud()
                ->putFile('covers/original', $request->file('cover'), 'private');

            ProcessTaleCover::dispatch($tale, Str::afterLast($path, '/'));
        }

        $this->saveRelationships($tale, $request);

        return redirect()->route('tales.show', $tale);
    }

    public function show(Tale $tale)
    {
        $tale->load([
            'director' => fn ($query) => $query->countAppearances(),
            'credits' => fn ($query) => $query->countAppearances(),
            'actors' => fn ($query) => $query->countAppearances(),
        ]);

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

        if ($request->boolean('remove_cover')) {
            $tale->cover = null;
            $tale->cover_placeholder = null;
        } elseif ($request->file('cover')) {
            $path = Storage::cloud()
                ->putFile('covers/original', $request->file('cover'), 'private');

            ProcessTaleCover::dispatch($tale, Str::afterLast($path, '/'));
        }

        $tale->save();

        $this->saveRelationships($tale, $request);

        return redirect()->route('tales.show', $tale);
    }

    private function saveRelationships(Tale $tale, StoreTale $request)
    {
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
    }
}
