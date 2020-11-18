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

        $tale->fill(
            $data = $request->validated()
        )->save();

        if ($request->file('cover')) {
            $path = Storage::cloud()
                ->putFile('covers/original', $request->file('cover'), 'private');

            ProcessTaleCover::dispatch($tale, Str::afterLast($path, '/'));
        }

        $this->saveRelationships($tale, $data);

        return redirect()->route('tales.show', $tale);
    }

    public function show(Tale $tale)
    {
        $tale->load([
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
        $tale->fill(
            $data = $request->validated()
        )->save();

        if ($request->boolean('remove_cover')) {
            $tale->removeCover();
        } elseif ($request->file('cover')) {
            $path = Storage::cloud()
                ->putFile('covers/original', $request->file('cover'), 'private');

            ProcessTaleCover::dispatch($tale, Str::afterLast($path, '/'));
        }

        $this->saveRelationships($tale, $data);

        return redirect()->route('tales.show', $tale);
    }

    private function saveRelationships(Tale $tale, array $data): void
    {
        $credits = collect($data['credits'] ?? [])
            ->keyBy(fn ($credit) => Artist::findBySlugOrNew($credit['artist'])->id)
            ->map(fn ($credit) => [
                'type' => $credit['type'],
                'nr' => $credit['nr'],
            ]);

        $tale->credits()->sync($credits);

        $actors = collect($data['actors'] ?? [])
            ->keyBy(fn ($credit) => Artist::findBySlugOrNew($credit['artist'])->id)
            ->map(fn ($credit) => [
                'credit_nr' => $credit['credit_nr'],
                'characters' => $credit['characters'],
            ]);

        $tale->actors()->sync($actors);
    }
}
