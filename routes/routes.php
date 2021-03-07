<?php

use Illuminate\Support\Facades\Route;

Route::redirect('/', '/bajki')
    ->name('home');

require __DIR__.'/auth.php';

require __DIR__.'/tales.php';

require __DIR__.'/artists.php';
