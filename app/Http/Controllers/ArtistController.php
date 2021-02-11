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

        $photoCrop = $request->photoCrop();

        $photoSource = $data['photo_source'] ?? null;

        if ($request->boolean('remove_photo')) {
            $artist->photo()->disassociate()->save();
        } elseif ($request->file('photo')) {
            $artist->photo()->associate(
                Photo::store($request->file('photo'), [
                    'crop' => $photoCrop,
                    'source' => $photoSource,
                ]),
            )->save();
        } elseif ($data['photo_uri'] ?? null) {
            $artist->photo()->associate(
                Photo::fromUrl($data['photo_uri'], [
                    'crop' => $photoCrop,
                    'source' => $photoSource,
                ]),
            )->save();
        } elseif ($artist->photo) {
            $artist->photo->forceFill([
                'source' => $photoSource,
                'crop' => $photoCrop,
            ])->save();
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
