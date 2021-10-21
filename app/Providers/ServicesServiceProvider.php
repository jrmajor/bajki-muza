<?php

namespace App\Providers;

use App\Services\Discogs;
use App\Services\FilmPolski;
use App\Services\Wikipedia;
use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;

class ServicesServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->registerServices();
        $this->registerAliases();
    }

    public function registerServices(): void
    {
        $this->app->singleton(Discogs::class, function (Application $app) {
            return new Discogs($app['config']['services.discogs.token']);
        });

        $this->app->singleton(Wikipedia::class);

        $this->app->singleton(FilmPolski::class);
    }

    protected function registerAliases(): void
    {
        $this->app->alias(Discogs::class, 'discogs');
        $this->app->alias(Wikipedia::class, 'wikipedia');
        $this->app->alias(FilmPolski::class, 'filmPolski');
    }
}
