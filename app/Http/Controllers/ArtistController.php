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
        $artist->fill($request->validated())->save();

        if ($request->boolean('remove_photo')) {
            $artist->photo = null;
            $artist->photo_placeholder = null;

            $artist->save();
        } elseif ($request->file('photo')) {
            $path = Storage::cloud()
                ->putFile('photos/original', $request->file('photo'), 'public');

            $artist->photo = Str::afterLast($path, '/');

            $artist->save();

            ProcessArtistPhoto::dispatch($artist);
        } elseif ($request->input('photo_uri')) {
            $photo = Http::get($request->input('photo_uri'));

            $filename = Str::random(40).'.jpeg';

            Storage::cloud()->put('photos/original/'.$filename, $photo->body(), 'public');

            $artist->photo = $filename;

            $artist->save();

            ProcessArtistPhoto::dispatch($artist);
        }

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
