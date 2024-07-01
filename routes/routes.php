<?php

use App\Http\Controllers\SitemapController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/bajki')
    ->name('home');

require __DIR__ . '/auth.php';

require __DIR__ . '/tales.php';

require __DIR__ . '/artists.php';

Route::get('sitemap.xml', SitemapController::class)
    ->name('sitemap');
