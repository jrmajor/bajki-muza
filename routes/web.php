<?php

Auth::routes(['register' => false, 'reset' => false, 'verify' => false]);

Route::redirect('/', '/bajki')->name('home');

Route::get('/bajki', 'TaleController@index')->name('tales.index');
Route::get('/bajki/{tale}', 'TaleController@show')->name('tales.show');

Route::get('/artysci', 'ArtistController@index')->name('artists.index');
Route::get('/artysci/{artist}', 'ArtistController@show')->name('artists.show');

Route::middleware('auth')->group(function () {
    Route::get('/bajki/create', 'TaleController@create')->name('tales.create');
    Route::post('/bajki', 'TaleController@store')->name('tales.store');
    Route::get('/bajki/{tale}/edit', 'TaleController@edit')->name('tales.edit');
    Route::match(['put', 'patch'], '/bajki/{photo}', 'TaleController@update')->name('tales.update');
    Route::delete('/bajki/{photo}', 'TaleController@destroy')->name('tales.destroy');

    Route::get('/artysci/create', 'ArtistController@create')->name('artists.create');
    Route::post('/artysci', 'ArtistController@store')->name('artists.store');
    Route::get('/artysci/{artist}/edit', 'ArtistController@edit')->name('artists.edit');
    Route::match(['put', 'patch'], '/artysci/{artist}', 'ArtistController@update')->name('artists.update');
    Route::delete('/artysci/{artist}', 'ArtistController@destroy')->name('artists.destroy');

});

Route::post('/cache/flush', 'CacheController@flush')->name('cache.flush');

Route::get('/db/artists', function () {
    foreach (DB::select('select * from artists') as $artist) {
        echo '$artist = new Artist();';
        echo PHP_EOL;
        echo '$artist->slug = \''.$artist->id."';";
        echo PHP_EOL;
        echo '$artist->name = \''.$artist->name."';";
        echo PHP_EOL;
        if ($artist->discogs) {
            echo '$artist->discogs = '.$artist->discogs.';'.PHP_EOL;
        }
        if ($artist->imdb) {
            echo '$artist->imdb = \''.$artist->imdb.'\';'.PHP_EOL;
        }
        if ($artist->wikipedia) {
            echo '$artist->wikipedia = \''.$artist->wikipedia.'\';'.PHP_EOL;
        }
        echo '$artist->save();';
        echo PHP_EOL;
        echo "DB::update(\n    \"update artists set created_at = ?, updated_at = ? where id = ?\",\n    ['".$artist->created_at."', '".$artist->updated_at.'\', $artist->id]'."\n);";
        echo PHP_EOL;
        echo PHP_EOL;
    }
});

Route::get('/db/tales', function () {
    foreach (DB::select('select * from tales') as $tale) {
        echo '$tale = new Tale();';
        echo PHP_EOL;
        echo '$tale->slug = \''.$tale->id."';";
        echo PHP_EOL;
        echo '$tale->title = \''.$tale->title."';";
        echo PHP_EOL;
        echo '$tale->year = \''.$tale->year."';";
        echo PHP_EOL;
        echo '$director = Artist::where(\'slug\', \''.$tale->director_id.'\')->get()->first();';
        echo PHP_EOL;
        echo '$tale->director_id = $director->id;';
        echo PHP_EOL;
        echo '$tale->nr = \''.$tale->nr."';";
        echo PHP_EOL;
        echo '$tale->cover = \''.$tale->cover."';";
        echo PHP_EOL;
        echo '$tale->save();';
        echo PHP_EOL;
        echo "DB::update(\n    \"update tales set created_at = ?, updated_at = ? where id = ?\",\n    ['".$tale->created_at."', '".$tale->updated_at.'\', $tale->id]'."\n);";
        echo PHP_EOL;
        echo PHP_EOL;
    }
});

Route::get('/db/tales-actors', function () {
    foreach (DB::select('select * from tales_actors') as $r) {
        //dd($r);
        echo '$artist = Artist::where(\'slug\', \''.$r->artist_id.'\')->get()->first();';
        echo PHP_EOL;
        echo '$tale = Tale::where(\'slug\', \''.$r->tale_id.'\')->get()->first();';
        echo PHP_EOL;
        echo '$tale->actors()->attach(';
        echo PHP_EOL;
        echo '    $artist->id,';
        echo PHP_EOL;
        echo '    [';
        echo PHP_EOL;
        echo '        \'credit_nr\' => '.$r->credit_nr.',';
        echo PHP_EOL;
        if ($r->characters) {
            echo '        \'characters\' => \''.$r->characters.'\''.PHP_EOL;
        }
        echo '    ]';
        echo PHP_EOL;
        echo ');';
        echo PHP_EOL;
        echo "DB::update(\n    \"update tales_actors set created_at = ?, updated_at = ? where artist_id = ? and tale_id = ?\",\n    ['".$r->created_at."', '".$r->updated_at.'\', $artist->id, $tale->id]'."\n);";
        echo PHP_EOL;
        echo PHP_EOL;
    }
});

Route::get('/db/tales-lyricists', function () {
    foreach (DB::select('select * from tales_lyricists') as $r) {
        echo '$artist = Artist::where(\'slug\', \''.$r->artist_id.'\')->get()->first();';
        echo PHP_EOL;
        echo '$tale = Tale::where(\'slug\', \''.$r->tale_id.'\')->get()->first();';
        echo PHP_EOL;
        echo '$tale->lyricists()->attach(';
        echo PHP_EOL;
        echo '    $artist->id,';
        echo PHP_EOL;
        echo '    [\'credit_nr\' => '.($r->credit_nr ?? 'null').']';
        echo PHP_EOL;
        echo ');';
        echo PHP_EOL;
        echo "DB::update(\n    \"update tales_lyricists set created_at = ?, updated_at = ? where artist_id = ? and tale_id = ?\",\n    ['".$r->created_at."', '".$r->updated_at.'\', $artist->id, $tale->id]'."\n);";
        echo PHP_EOL;
        echo PHP_EOL;
    }
});

Route::get('/db/tales-composers', function () {
    foreach (DB::select('select * from tales_composers') as $r) {
        echo '$artist = Artist::where(\'slug\', \''.$r->artist_id.'\')->get()->first();';
        echo PHP_EOL;
        echo '$tale = Tale::where(\'slug\', \''.$r->tale_id.'\')->get()->first();';
        echo PHP_EOL;
        echo '$tale->composers()->attach(';
        echo PHP_EOL;
        echo '    $artist->id,';
        echo PHP_EOL;
        echo '    [\'credit_nr\' => '.($r->credit_nr ?? 'null').']';
        echo PHP_EOL;
        echo ');';
        echo PHP_EOL;
        echo "DB::update(\n    \"update tales_composers set created_at = ?, updated_at = ? where artist_id = ? and tale_id = ?\",\n    ['".$r->created_at."', '".$r->updated_at.'\', $artist->id, $tale->id]'."\n);";
        echo PHP_EOL;
        echo PHP_EOL;
    }
});
