<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTale;
use App\Images\Cover;
use App\Models\Tale;
use Illuminate\Contracts\View\View;
use Symfony\Component\HttpFoundation\Response;

class TaleController extends Controller
{
    public function create(): View
    {
        return view('tales.create');
    }

    public function store(StoreTale $request): Response
    {
        $tale = Tale::create($request->validated());

        $tale->syncCredits($request->creditsData());

        $tale->actors()->sync($request->actorsData());

        if ($cover = $request->file('cover')) {
            $tale->cover()->associate(Cover::store($cover));
        }

        return redirect()->route('tales.show', tap($tale)->push());
    }

    public function show(Tale $tale): View
    {
        $tale->load([
            'credits' => fn ($query) => $query->countAppearances(),
            'actors' => fn ($query) => $query->countAppearances(),
        ]);

        return view('tales.show', ['tale' => $tale]);
    }

    public function edit(Tale $tale): View
    {
        return view('tales.edit', ['tale' => $tale]);
    }

    public function update(StoreTale $request, Tale $tale): Response
    {
        $tale->fill($request->validated());

        $tale->syncCredits($request->creditsData());

        $tale->actors()->sync($request->actorsData());

        if ($request->boolean('remove_cover')) {
            $tale->cover()->dissociate();
        } elseif ($cover = $request->file('cover')) {
            $tale->cover()->associate(Cover::store($cover));
        }

        return redirect()->route('tales.show', tap($tale)->push());
    }
}
