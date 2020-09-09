<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTale;
use App\Jobs\ProcessTaleCover;
use App\Models\Artist;
use App\Models\Tale;
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

        if ($request->file('cover')) {
            $path = $request->file('cover')->storePublicly('covers/original', 's3');

            $tale->cover = Str::afterLast($path, '/');

            $tale->save();

            ProcessTaleCover::dispatch($tale);
        } else {
            $tale->save();
        }

        $this->saveRelationships($tale, $request);

        return redirect()->route('tales.show', $tale);
    }

    public function show(Tale $tale)
    {
        $tale->load([
            'director' => fn ($query) => $query->countAppearances(),
            'lyricists' => fn ($query) => $query->countAppearances(),
            'composers' => fn ($query) => $query->countAppearances(),
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

        if ($request->input('remove_cover')) {
            $tale->cover = null;
            $tale->cover_placeholder = null;

            $tale->save();
        } elseif ($request->file('cover')) {
            $path = $request->file('cover')->storePublicly('covers/original', 's3');

            $tale->cover = Str::afterLast($path, '/');

            $tale->save();

            ProcessTaleCover::dispatch($tale);
        } else {
            $tale->save();
        }

        $this->saveRelationships($tale, $request);

        return redirect()->route('tales.show', $tale);
    }

    public function destroy(Tale $tale)
    {
        //
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
