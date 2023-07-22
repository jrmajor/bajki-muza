<?php

use Illuminate\Support\Facades\Facade;
use Illuminate\Support\ServiceProvider;

return [

    'name' => 'Bajki Polskich Nagrań „Muza”',

    'env' => env('APP_ENV', 'production'),
    'debug' => (bool) env('APP_DEBUG', false),

    'url' => env('APP_URL', 'http://bajki-muza.test'),
    'asset_url' => env('ASSET_URL'),

    'timezone' => 'Europe/Warsaw',
    'locale' => 'pl',
    'fallback_locale' => 'pl',
    'faker_locale' => 'pl_PL',

    'key' => env('APP_KEY'),
    'cipher' => 'AES-256-CBC',

    'admin_id' => (int) env('ADMIN_ID'),

    'maintenance' => [
        'driver' => 'file',
        // 'store'  => 'redis',
    ],

    'providers' => ServiceProvider::defaultProviders()->merge([
        App\Providers\AppServiceProvider::class,
        App\Providers\AuthServiceProvider::class,
        App\Providers\EventServiceProvider::class,
        App\Providers\MacrosServiceProvider::class,
        App\Providers\RouteServiceProvider::class,
        App\Providers\TelescopeServiceProvider::class,
        App\Providers\ServicesServiceProvider::class,
    ])->toArray(),

    'aliases' => Facade::defaultAliases()->merge([
        'CreditType' => App\Values\CreditType::class,
    ])->toArray(),

];
