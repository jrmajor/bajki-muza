<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreArtist;
use App\Images\Photo;
use App\Models\Artist;
use Illuminate\Contracts\View\View;
use Symfony\Component\HttpFoundation\Response;

class ArtistController extends Controller
{
    public function show(Artist $artist): View
    {
        return view('artists.show', ['artist' => $artist]);
    }

    public function edit(Artist $artist): View
    {
        return view('artists.edit', ['artist' => $artist]);
    }

    public function update(StoreArtist $request, Artist $artist): Response
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

    public function destroy(Artist $artist): Response
    {
        $artist->delete();

        return redirect()->route('artists.index');
    }
}
