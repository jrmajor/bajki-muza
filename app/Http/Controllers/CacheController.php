<?php

namespace App\Http\Controllers;

use App\Artist;
use Illuminate\Http\Request;

class CacheController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function flush(Request $request)
    {
        if ($request->input('type') == 'artist') {
            Artist::findBySlugOrFail($request->input('slug'))->flushCache();
        } else {
            throw new Exception();
        }

        return redirect()->route($request->input('type').'s.show', [$request->input('type') => $request->input('slug')]);
    }
}
