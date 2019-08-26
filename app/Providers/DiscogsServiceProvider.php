<?php

namespace App\Providers;

use Discogs\ClientFactory;
use Illuminate\Support\ServiceProvider;

class DiscogsServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('discogs', function ($app) {
            return ClientFactory::factory([
                'defaults' => [
                    'headers' => config('discogs.headers'),
                    'query'   => [
                        'token' => config('discogs.token'),
                    ],
                ],
            ]);
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //$oauth = $this->app->make('oauth');
        //$this->app->make('discogs')->getHttpClient()->getEmitter()->attach($oauth);
    }
}
