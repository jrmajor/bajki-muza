<?php

use App\Http\Controllers\TaleController;
use Illuminate\Support\Facades\Route;

Route::get('bajki', [TaleController::class, 'index'])
    ->name('tales.index');

Route::get('bajki/create', [TaleController::class, 'create'])
    ->middleware('auth')->name('tales.create');

Route::post('bajki', [TaleController::class, 'store'])
    ->middleware('auth')->name('tales.store');

Route::get('bajki/{tale}', [TaleController::class, 'show'])
    ->name('tales.show');

Route::get('bajki/{tale}/edit', [TaleController::class, 'edit'])
    ->middleware('auth')->name('tales.edit');

Route::match(['put', 'patch'], '/bajki/{tale}', [TaleController::class, 'update'])
    ->middleware('auth')->name('tales.update');
