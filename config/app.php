<?php

use Illuminate\Support\Facades\Facade;

return [

    'name' => env('APP_NAME', 'Bajki Polskich Nagrań „Muza”'),

    'env' => env('APP_ENV', 'production'),
    'debug' => (bool) env('APP_DEBUG', false),

    'url' => env('APP_URL', 'http://bajki-muza.test'),
    'asset_url' => env('ASSET_URL', null),

    'timezone' => 'Europe/Warsaw',

    'locale' => 'pl',
    'fallback_locale' => 'pl',
    'faker_locale' => 'pl_PL',

    'key' => env('APP_KEY'),
    'cipher' => 'AES-256-CBC',

    'admin_id' => (int) env('ADMIN_ID'),

    'providers' => [

        // Laravel Framework Service Providers...
        Illuminate\Auth\AuthServiceProvider::class,
        Illuminate\Bus\BusServiceProvider::class,
        Illuminate\Cache\CacheServiceProvider::class,
        Illuminate\Foundation\Providers\ConsoleSupportServiceProvider::class,
        Illuminate\Cookie\CookieServiceProvider::class,
        Illuminate\Database\DatabaseServiceProvider::class,
        Illuminate\Encryption\EncryptionServiceProvider::class,
        Illuminate\Filesystem\FilesystemServiceProvider::class,
        Illuminate\Foundation\Providers\FoundationServiceProvider::class,
        Illuminate\Hashing\HashServiceProvider::class,
        Illuminate\Mail\MailServiceProvider::class,
        Illuminate\Notifications\NotificationServiceProvider::class,
        Illuminate\Pagination\PaginationServiceProvider::class,
        Illuminate\Pipeline\PipelineServiceProvider::class,
        Illuminate\Queue\QueueServiceProvider::class,
        Illuminate\Redis\RedisServiceProvider::class,
        Illuminate\Auth\Passwords\PasswordResetServiceProvider::class,
        Illuminate\Session\SessionServiceProvider::class,
        Illuminate\Translation\TranslationServiceProvider::class,
        Illuminate\Validation\ValidationServiceProvider::class,
        Illuminate\View\ViewServiceProvider::class,

        // Package Service Providers...

        // Application Service Providers...
        App\Providers\AppServiceProvider::class,
        App\Providers\AuthServiceProvider::class,
        App\Providers\EventServiceProvider::class,
        App\Providers\MacrosServiceProvider::class,
        App\Providers\RouteServiceProvider::class,
        App\Providers\TelescopeServiceProvider::class,
        App\Providers\ServicesServiceProvider::class,

    ],

    'aliases' => Facade::defaultAliases()->merge([
        'CreditType' => App\Values\CreditType::class,
    ])->toArray(),

];
