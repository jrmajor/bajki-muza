<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTale;
use App\Http\Resources\Tales\EditResource;
use App\Http\Resources\Tales\IndexResource;
use App\Http\Resources\Tales\ShowResource;
use App\Images\Cover;
use App\Models\Tale;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class TaleController extends Controller
{
    public function index(Request $request): Response
    {
        $tales = Tale::query()
            ->withActorsPopularity()
            ->unless(
                blank($request->string('search')),
                fn (Builder $q) => $q->where('title', 'like', '%' . $request->string('search') . '%'),
            )
            ->unless(
                blank($request->input('discogs')),
                fn (Builder $q) => $q->whereNull('discogs', not: $request->boolean('discogs')),
            )
            ->orderByDesc('popularity')
            ->orderBy('year')
            ->orderBy('title')
            ->paginate(20);

        return Inertia::render(
            'Tales/Index',
            IndexResource::collection($tales)->toResponse($request)->getData(true),
        );
    }

    public function create(): View
    {
        return view('tales.create');
    }

    public function store(StoreTale $request): RedirectResponse
    {
        $tale = Tale::create($request->validated());

        $tale->syncCredits($request->creditsData());

        $tale->actors()->sync($request->actorsData());

        if ($cover = $request->oneFile('cover')) {
            $tale->cover()->associate(Cover::store($cover));
        }

        return redirect()->route('tales.show', tap($tale)->push());
    }

    public function show(Tale $tale): Response
    {
        $tale->load([
            'credits' => fn ($query) => $query->countAppearances(),
            'actors' => fn ($query) => $query->countAppearances(),
        ]);

        return Inertia::render('Tales/Show', ['tale' => new ShowResource($tale)]);
    }

    public function oldEdit(Tale $tale): View
    {
        return view('tales.edit', ['tale' => $tale]);
    }

    public function edit(Tale $tale): Response
    {
        return Inertia::render('Tales/Edit', ['tale' => new EditResource($tale)]);
    }

    public function update(StoreTale $request, Tale $tale): RedirectResponse
    {
        $tale->fill($request->validated());

        $tale->syncCredits($request->creditsData());

        $tale->actors()->sync($request->actorsData());

        if ($request->boolean('remove_cover')) {
            $tale->cover()->dissociate();
        } elseif ($cover = $request->oneFile('cover')) {
            $tale->cover()->associate(Cover::store($cover));
        }

        return redirect()->route('tales.show', tap($tale)->push());
    }
}
