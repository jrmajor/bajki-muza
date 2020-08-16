<?php

namespace App\Http\Controllers;

use App\Artist;
use Illuminate\Http\Request;

class ArtistController extends Controller
{
    public function index()
    {
        $artists = Artist::orderBy('name')
                        ->paginate(30);

        return view('artists.index', ['artists' => $artists]);
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        //
    }

    public function show($slug)
    {
        $artist = Artist::findBySlugOrFail($slug);

        return view('artists.show', ['artist' => $artist]);
    }

    public function edit($slug)
    {
        $artist = Artist::findBySlugOrFail($slug);

        return view('artists.edit', ['artist' => $artist]);
    }

    public function update(Request $request, $slug)
    {
        $artist = Artist::findBySlugOrFail($slug);

        $artist->name = $request->input('name');
        $artist->discogs = $request->input('discogs');
        $artist->imdb = $request->input('imdb');
        $artist->wikipedia = $request->input('wikipedia');
        $artist->save();

        $artist->flushCache();

        return redirect()->route('artists.show', $artist->slug);
    }

    public function destroy($slug)
    {
        $artist = Artist::findBySlugOrFail($slug);
        $artist->delete();

        return redirect()->route('artists.index');
    }
}
