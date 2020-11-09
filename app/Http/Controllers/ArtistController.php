<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreArtist;
use App\Jobs\ProcessArtistPhoto;
use App\Models\Artist;
use App\Values\ArtistPhotoCrop;
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
        $artist
            ->fill($data = $request->validated())
            ->save();

        $photoCrop = $data['photo_crop'] ?? null !== null
            ? ArtistPhotoCrop::fromStrings($data['photo_crop'])
            : null;

        if ($request->boolean('remove_photo')) {
            $artist->removePhoto();
        } elseif ($request->file('photo')) {
            $path = Storage::cloud()
                ->putFile('photos/original', $request->file('photo'), 'private');

            ProcessArtistPhoto::dispatch($artist, Str::afterLast($path, '/'), $photoCrop);
        } elseif ($data['photo_uri'] ?? null) {
            $photo = Http::get($data['photo_uri']);

            $filename = Str::random(40).'.jpeg';

            Storage::cloud()->put('photos/original/'.$filename, $photo->body(), 'private');

            ProcessArtistPhoto::dispatch($artist, $filename, $photoCrop);
        } elseif (
            $artist->photo
            && optional($photoCrop)->toArray() != optional($artist->photo_crop)->toArray()
        ) {
            ProcessArtistPhoto::dispatch($artist, $artist->photo, $photoCrop);
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
