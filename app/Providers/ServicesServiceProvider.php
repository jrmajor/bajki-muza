<?php

namespace App\Providers;

use App\Services\Discogs;
use App\Services\FilmPolski;
use App\Services\Wikipedia;
use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;
use Intervention\Image\Drivers;
use Intervention\Image\ImageManager;
use Intervention\Image\Interfaces\ImageManagerInterface;

class ServicesServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(Discogs::class, function (Application $app) {
            return new Discogs($app['config']['services.discogs.token']);
        });

        $this->app->singleton(Wikipedia::class);

        $this->app->singleton(FilmPolski::class);

        $this->app->singleton(ImageManagerInterface::class, function () {
            return new ImageManager(new Drivers\Gd\Driver());
        });
    }
}
