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
        $tale = new Tale();

        $tale->fill(
            $data = $request->validated(),
        )->save();

        $tale->syncCredits($request->creditsData());

        $tale->actors()->sync($request->actorsData());

        if ($request->file('cover')) {
            $tale->cover()->associate(
                Cover::store($request->file('cover')),
            )->save();
        }

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
            $data = $request->validated(),
        )->save();

        $tale->syncCredits($request->creditsData());

        $tale->actors()->sync($request->actorsData());

        if ($request->boolean('remove_cover')) {
            $tale->cover()->disassociate()->save();
        } elseif ($request->file('cover')) {
            $tale->cover()->associate(
                Cover::store($request->file('cover')),
            )->save();
        }

        return redirect()->route('tales.show', $tale);
    }
}
