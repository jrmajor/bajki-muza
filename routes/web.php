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
