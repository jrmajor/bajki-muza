<?php

namespace App\Providers;

use App\Services\Discogs;
use App\Services\Wikipedia;
use Illuminate\Support\ServiceProvider;

class ServicesServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(Wikipedia::class);

        $this->app->singleton(Discogs::class, function ($app) {
           return new Discogs($app->config->get('services.discogs.token'));
        });
    }
}
