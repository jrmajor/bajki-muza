<?php

namespace App\Providers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\ServiceProvider;
use Inertia\Inertia;

class AppServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Inertia::setRootView('layouts.app-inertia');

        Relation::requireMorphMap();

        $shouldBeStrict = ! $this->app->environment('production');

        Model::preventLazyLoading($shouldBeStrict);
        // Model::preventSilentlyDiscardingAttributes($shouldBeStrict);
        Model::preventAccessingMissingAttributes($shouldBeStrict);
    }
}
