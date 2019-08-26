<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Artist;

class CacheController extends Controller
{
    /**
     * Instantiate a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function flush(Request $request) {
        if ($request->input('type') == 'artist')
            Artist::findBySlugOrFail($request->input('id'))->flushCache();
        else
            throw new Exception();

        return redirect()->route($request->input('type') . 's.show', [$request->input('type') => $request->input('slug')]);
    }
}
