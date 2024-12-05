<?php

namespace App\Providers;

use App\Http\InertiaHttpGateway;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\ServiceProvider;
use Inertia\Ssr\Gateway;

class AppServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Relation::requireMorphMap();

        $shouldBeStrict = ! $this->app->environment('production');

        Model::preventLazyLoading($shouldBeStrict);
        // Model::preventSilentlyDiscardingAttributes($shouldBeStrict);
        Model::preventAccessingMissingAttributes($shouldBeStrict);

        $this->app->bind(Gateway::class, InertiaHttpGateway::class);
    }
}
