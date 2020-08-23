<?php

use Illuminate\Support\Facades\Route;

Auth::routes([
    'register' => false,
    'reset' => false,
    'confirm' => false,
    'verify' => false,
]);

Route::redirect('/', '/bajki')->name('home');

Route::middleware('auth')->group(function () {
    Route::get('/bajki/create', 'TaleController@create')->name('tales.create');
    Route::post('/bajki', 'TaleController@store')->name('tales.store');
    Route::get('/bajki/{tale}/edit', 'TaleController@edit')->name('tales.edit');
    Route::match(['put', 'patch'], '/bajki/{tale}', 'TaleController@update')->name('tales.update');
    // Route::delete('/bajki/{tale}', 'TaleController@destroy')->name('tales.destroy');

    Route::get('/artysci/{artist}/edit', 'ArtistController@edit')->name('artists.edit');
    Route::match(['put', 'patch'], '/artysci/{artist}', 'ArtistController@update')->name('artists.update');
    Route::post('/artysci/{artist}/flush-cache', 'ArtistController@flushCache')->name('artists.flushCache');
    Route::delete('/artysci/{artist}', 'ArtistController@destroy')->name('artists.destroy');

    Route::get('/ajax/artists', 'AjaxController@artists')->name('ajax.artists');

    Route::get('/ajax/discogs', 'AjaxController@discogs');
    Route::get('/ajax/filmpolski', 'AjaxController@filmPolski');
    Route::get('/ajax/wikipedia', 'AjaxController@wikipedia');
});

Route::livewire('/bajki', 'tales')->name('tales.index');
Route::get('/bajki/{tale}', 'TaleController@show')->name('tales.show');

Route::livewire('/artysci', 'artists')->name('artists.index');
Route::get('/artysci/{artist}', 'ArtistController@show')->name('artists.show');
