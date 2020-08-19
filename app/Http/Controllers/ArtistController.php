<?php

namespace App\Http\Controllers;

use App\Artist;
use App\Http\Requests\StoreArtist;

class ArtistController extends Controller
{
    public function index()
    {
        return view('artists.index', [
            'artists' => Artist::orderBy('name')->paginate(30),
        ]);
    }

    public function create()
    {
        //
    }

    public function store(StoreArtist $request)
    {
        //
    }

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
