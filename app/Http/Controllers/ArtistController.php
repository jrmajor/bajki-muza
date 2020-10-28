<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreArtist;
use App\Jobs\ProcessArtistPhoto;
use App\Models\Artist;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

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
        $data = $request->validated();

        $artist->fill($data)->save();

        if ($request->boolean('remove_photo')) {
            $artist->photo = null;
            $artist->photo_source = null;
            $artist->photo_width = null;
            $artist->photo_height = null;
            $artist->photo_crop = null;
            $artist->photo_placeholder = null;
        } elseif ($request->file('photo')) {
            $path = Storage::cloud()
                ->putFile('photos/original', $request->file('photo'), 'private');

            ProcessArtistPhoto::dispatch(
                $artist,
                Str::afterLast($path, '/'),
                $data['photo_crop']
            );
        } elseif ($data['photo_uri'] ?? null) {
            $photo = Http::get($data['photo_uri']);

            $filename = Str::random(40).'.jpeg';

            Storage::cloud()->put('photos/original/'.$filename, $photo->body(), 'private');

            ProcessArtistPhoto::dispatch(
                $artist,
                $filename,
                $data['photo_crop']
            );
        } elseif ($artist->photo && ($data['photo_crop'] ?? null) != $artist->photo_crop) {
            ProcessArtistPhoto::dispatch(
                $artist,
                $artist->photo,
                $data['photo_crop']
            );
        }

        $artist->save();

        $artist->flushCache();

        return redirect()->route('artists.show', $artist);
    }

    public function flushCache(Artist $artist)
    {
        $artist->flushCache();

        return redirect()->route('artists.show', $artist);
    }

    public function destroy(Artist $artist)
    {
        $artist->delete();

        return redirect()->route('artists.index');
    }
}
