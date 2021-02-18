<?php

namespace App\Providers;

use App\Services\Discogs;
use App\Services\FilmPolski;
use App\Services\Wikipedia;
use Illuminate\Support\ServiceProvider;

class ServicesServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->when(Discogs::class)
            ->needs('$token')
            ->giveConfig('services.discogs.token');

        $this->app->singleton(Discogs::class);

        $this->app->singleton(Wikipedia::class);

        $this->app->singleton(FilmPolski::class);
    }
}
