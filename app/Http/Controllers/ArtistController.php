<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreArtist;
use App\Http\Resources\Artists\IndexResource;
use App\Http\Resources\Artists\ShowResource;
use App\Images\Photo;
use App\Models\Artist;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ArtistController extends Controller
{
    public function index(Request $request): Response
    {
        $artists = Artist::query()
            ->countAppearances()
            ->unless(
                blank($request->string('search')),
                fn (Builder $q) => $q->where('name', 'like', '%' . $request->string('search') . '%'),
            )
            ->orderByDesc('appearances')
            ->orderBy('name')
            ->paginate(30);

        return Inertia::render(
            'Artists/Index',
            IndexResource::collection($artists)->toResponse($request)->getData(true),
        );
    }

    public function oldShow(Artist $artist): View
    {
        return view('artists.show', ['artist' => $artist]);
    }

    public function show(Artist $artist): Response
    {
        return Inertia::render('Artists/Show', ['artist' => new ShowResource($artist)]);
    }

    public function edit(Artist $artist): View
    {
        return view('artists.edit', ['artist' => $artist]);
    }

    public function update(StoreArtist $request, Artist $artist): RedirectResponse
    {
        $artist->fill($request->validated());

        if ($request->boolean('remove_photo')) {
            $artist->photo()->dissociate();
        } elseif ($photo = $request->uploadedPhoto()) {
            $artist->photo()->associate(
                Photo::store($photo, $request->photoData()),
            );
        } elseif ($artist->photo) {
            $artist->photo->forceFill($request->photoData());
        }

        return redirect()->route('artists.show', tap($artist)->push());
    }

    public function destroy(Artist $artist): RedirectResponse
    {
        $artist->delete();

        return redirect()->route('artists.index');
    }
}
