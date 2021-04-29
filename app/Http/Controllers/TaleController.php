<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTale;
use App\Images\Cover;
use App\Models\Tale;

class TaleController extends Controller
{
    public function create()
    {
        return view('tales.create');
    }

    public function store(StoreTale $request)
    {
        $tale = Tale::create($request->validated());

        $tale->syncCredits($request->creditsData());

        $tale->actors()->sync($request->actorsData());

        if ($request->file('cover')) {
            $tale->cover()->associate(
                Cover::store($request->file('cover')),
            );
        }

        return redirect()->route('tales.show', tap($tale)->push());
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
        $tale->fill($request->validated());

        $tale->syncCredits($request->creditsData());

        $tale->actors()->sync($request->actorsData());

        if ($request->boolean('remove_cover')) {
            $tale->cover()->dissociate();
        } elseif ($request->file('cover')) {
            $tale->cover()->associate(
                Cover::store($request->file('cover')),
            );
        }

        return redirect()->route('tales.show', tap($tale)->push());
    }
}
