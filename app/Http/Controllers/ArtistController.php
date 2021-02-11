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
        $artist->fill(
            $data = $request->validated(),
        )->save();

        if ($request->boolean('remove_photo')) {
            $artist->photo()->disassociate()->save();
        } elseif ($request->file('photo')) {
            $artist->photo()->associate(
                Photo::store($request->file('photo'), $request->photoData()),
            )->save();
        } elseif ($request->photo_uri) {
            $artist->photo()->associate(
                Photo::fromUrl($data['photo_uri'], $request->photoData()),
            )->save();
        } elseif ($artist->photo) {
            $artist->photo->forceFill(
                $request->photoData(),
            )->save();
        }

        $artist->flushCache();

        return redirect()->route('artists.show', $artist);
    }

    public function destroy(Artist $artist)
    {
        $artist->delete();

        return redirect()->route('artists.index');
    }
}
