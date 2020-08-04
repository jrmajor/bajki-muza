<?php

namespace App\Http\Controllers;

use App\Artist;
use App\Tale;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TaleController extends Controller
{
    public function index()
    {
        $tales = Tale::orderBy('year')
                        ->orderBy('title')
                        ->paginate(25);

        return view('tales.index', ['tales' => $tales]);
    }

    public function create()
    {
        $tale = [
            'title'     => '', 'year' => '', 'director' => '', 'cover' => '', 'nr' => '',
            'lyricists' => [['credit_nr' => '', 'artist' => '']],
            'composers' => [['credit_nr' => '', 'artist' => '']],
            'actors'    => [
                ['credit_nr' => '1', 'artist' => '', 'characters' => ''],
                ['credit_nr' => '2', 'artist' => '', 'characters' => ''],
                ['credit_nr' => '3', 'artist' => '', 'characters' => ''],
                ['credit_nr' => '4', 'artist' => '', 'characters' => ''],
                ['credit_nr' => '5', 'artist' => '', 'characters' => ''],
                ['credit_nr' => '6', 'artist' => '', 'characters' => ''],
                ['credit_nr' => '7', 'artist' => '', 'characters' => ''],
                ['credit_nr' => '8', 'artist' => '', 'characters' => ''],
                ['credit_nr' => '9', 'artist' => '', 'characters' => ''],
                ['credit_nr' => '10', 'artist' => '', 'characters' => ''],
            ],
        ];

        return view('tales.create', ['tale' => $tale]);
    }

    public function store(Request $request)
    {
        $tale = new Tale();
        $tale->slug = Str::slug($request->input('title'));
        $tale->title = $request->input('title');
        $tale->year = $request->input('year');
        if ($request->input('director')) {
            $tale->director_id = Artist::findBySlugOrNew($request->input('director'))->id;
        }
        $tale->nr = $request->input('nr');
        $tale->cover = $request->input('cover');
        $tale->save();

        foreach ($request->input('lyricists') as $lyricist) {
            $artist = Artist::findBySlugOrNew($lyricist['artist']);
            $tale->lyricists()->attach(
                $artist->id,
                ['credit_nr' => $lyricist['credit_nr']]
            );
        }

        foreach ($request->input('composers') as $composer) {
            $artist = Artist::findBySlugOrNew($composer['artist']);
            $tale->composers()->attach(
                $artist->id,
                ['credit_nr' => $composer['credit_nr']]
            );
        }

        foreach ($request->input('actors') as $actor) {
            $artist = Artist::findBySlugOrNew($actor['artist']);
            $tale->actors()->attach(
                $artist->id,
                [
                    'credit_nr'  => $actor['credit_nr'],
                    'characters' => $actor['characters'],
                ]
            );
        }

        return redirect()->route('tales.show', ['tale' => $tale->slug]);
    }

    public function show($slug)
    {
        $tale = Tale::findBySlugOrFail($slug);

        return view('tales.show', ['tale' => $tale]);
    }

    public function edit($slug)
    {
        $tale = Tale::findBySlugOrFail($slug);

        return view('tales.edit', ['tale' => $tale]);
    }

    public function update(Request $request, $slug)
    {
        $tale = Tale::findBySlugOrFail($slug);
        $tale->slug = Str::slug($request->input('title'));
        $tale->title = $request->input('title');
        $tale->year = $request->input('year');
        if ($request->input('director')) {
            $tale->director_id = Artist::findBySlugOrNew($request->input('director'))->id;
        }
        $tale->nr = $request->input('nr');
        $tale->cover = $request->input('cover');
        $tale->save();

        $lyricists = [];
        foreach ($request->input('lyricists') as $lyricist) {
            $artist = Artist::findBySlugOrNew($lyricist['artist']);
            $lyricists[$artist->id] = [
                'credit_nr' => $lyricist['credit_nr'],
            ];
        }
        $tale->lyricists()->sync($lyricists);

        $composers = [];
        foreach ($request->input('composers') as $composer) {
            $artist = Artist::findBySlugOrNew($composer['artist']);
            $composers[$artist->id] = [
                'credit_nr' => $composer['credit_nr'],
            ];
        }
        $tale->composers()->sync($composers);

        $actors = [];
        foreach ($request->input('actors') as $actor) {
            $artist = Artist::findBySlugOrNew($actor['artist']);
            $actors[$artist->id] = [
                'credit_nr'  => $actor['credit_nr'],
                'characters' => $actor['characters'],
            ];
        }
        $tale->actors()->sync($actors);

        return redirect()->route('tales.show', ['tale' => $tale->slug]);
    }

    public function destroy($slug)
    {
        //
    }
}
