<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreArtist;
use App\Images\Photo;
use App\Models\Artist;

class ArtistController extends Controller
{
    public function show(Artist $artist)
    {
        return view('artists.show', ['artist' => $artist]);
    }

    public function edit(Artist $artist)
    {
        return view('artists.edit', ['artist' => $artist]);
    }

    public function update(StoreArtist $request, Artist $artist)
    {
        $artist->fill($request->validated());

        if ($request->boolean('remove_photo')) {
            $artist->photo()->dissociate();
        } elseif ($request->uploadedPhoto()) {
            $artist->photo()->associate(
                Photo::store($request->uploadedPhoto(), $request->photoData()),
            );
        } elseif ($artist->photo) {
            $artist->photo->forceFill($request->photoData());
        }

        return redirect()->route('artists.show', tap($artist)->push());
    }

    public function destroy(Artist $artist)
    {
        $artist->delete();

        return redirect()->route('artists.index');
    }
}
