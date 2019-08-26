<?php

namespace App\Http\Controllers;

use App\Artist;
use Illuminate\Http\Request;

class ArtistController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $artists = Artist::orderBy('name')
                        ->paginate(30);

        return view('artists.index', ['artists' => $artists]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param int $slug
     *
     * @return \Illuminate\Http\Response
     */
    public function show($slug)
    {
        $artist = Artist::findBySlugOrFail($slug);

        return view('artists.show', ['artist' => $artist]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $slug
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($slug)
    {
        $artist = Artist::findBySlugOrFail($slug);

        return view('artists.edit', ['artist' => $artist]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int                      $slug
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $slug)
    {
        $artist = Artist::findBySlugOrFail($slug);

        $artist->name = $request->input('name');
        $artist->discogs = $request->input('discogs');
        $artist->imdb = $request->input('imdb');
        $artist->wikipedia = $request->input('wikipedia');
        $artist->save();

        $artist->flushCache();

        return redirect()->route('artists.show', ['artist' => $artist->slug]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $slug
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($slug)
    {
        $artist = Artist::findBySlugOrFail($slug);
        $artist->delete();

        return redirect()->route('artists.index');
    }
}
