<?php

use App\Http\Controllers\AjaxController;
use App\Http\Controllers\ArtistController;
use App\Livewire\Artists;
use Illuminate\Support\Facades\Route;

Route::get('old/artysci', Artists::class);

Route::get('artysci', [ArtistController::class, 'index'])
    ->name('artists.index');

Route::get('artysci/{artist}', [ArtistController::class, 'show'])
    ->name('artists.show');

Route::get('artysci/{artist}/edit', [ArtistController::class, 'edit'])
    ->middleware('auth')->name('artists.edit');

Route::match(['put', 'patch'], '/artysci/{artist}', [ArtistController::class, 'update'])
    ->middleware('auth')->name('artists.update');

Route::delete('artysci/{artist}', [ArtistController::class, 'destroy'])
    ->middleware('auth')->name('artists.destroy');

Route::get('ajax/artists', [AjaxController::class, 'artists'])
    ->middleware('auth')->name('ajax.artists');

Route::get('ajax/discogs', [AjaxController::class, 'discogs'])
    ->middleware('auth')->name('ajax.discogs');

Route::get('ajax/filmpolski', [AjaxController::class, 'filmPolski'])
    ->middleware('auth')->name('ajax.filmPolski');

Route::get('ajax/wikipedia', [AjaxController::class, 'wikipedia'])
    ->middleware('auth')->name('ajax.wikipedia');
