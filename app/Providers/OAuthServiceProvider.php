<?php

namespace App\Providers;

use GuzzleHttp\Subscriber\Oauth\Oauth1;
use Illuminate\Support\ServiceProvider;

class OAuthServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('oauth', function ($app) {
            return new Oauth1(config('discogs.oauth'));
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
