<?php

use App\Http\Controllers\AjaxController;
use App\Http\Controllers\ArtistController;
use App\Http\Controllers\TaleController;
use App\Http\Livewire\Artists;
use App\Http\Livewire\Tales;
use Illuminate\Support\Facades\Route;

Auth::routes([
    'register' => false,
    'reset' => false,
    'confirm' => false,
    'verify' => false,
]);

Route::redirect('/', '/bajki')->name('home');

Route::middleware('auth')->group(function () {
    Route::get('/bajki/create', [TaleController::class, 'create'])->name('tales.create');
    Route::post('/bajki', [TaleController::class, 'store'])->name('tales.store');
    Route::get('/bajki/{tale}/edit', [TaleController::class, 'edit'])->name('tales.edit');
    Route::match(['put', 'patch'], '/bajki/{tale}', [TaleController::class, 'update'])->name('tales.update');

    Route::get('/artysci/{artist}/edit', [ArtistController::class, 'edit'])->name('artists.edit');
    Route::match(['put', 'patch'], '/artysci/{artist}', [ArtistController::class, 'update'])->name('artists.update');
    Route::delete('/artysci/{artist}', [ArtistController::class, 'destroy'])->name('artists.destroy');

    Route::get('/ajax/artists', [AjaxController::class, 'artists'])->name('ajax.artists');

    Route::get('/ajax/discogs', [AjaxController::class, 'discogs'])->name('ajax.discogs');
    Route::get('/ajax/filmpolski', [AjaxController::class, 'filmPolski'])->name('ajax.filmPolski');
    Route::get('/ajax/wikipedia', [AjaxController::class, 'wikipedia'])->name('ajax.wikipedia');
});

Route::get('/bajki', Tales::class)->name('tales.index');
Route::get('/bajki/{tale}', [TaleController::class, 'show'])->name('tales.show');

Route::get('/artysci', Artists::class)->name('artists.index');
Route::get('/artysci/{artist}', [ArtistController::class, 'show'])->name('artists.show');
