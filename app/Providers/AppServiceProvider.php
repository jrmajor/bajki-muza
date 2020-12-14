<?php

namespace App\Providers;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Schema\ForeignIdColumnDefinition;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register() { }

    public function boot()
    {
        Blade::directive('encodedjson', function ($expression) {
            return "<?php echo e(json_encode($expression)) ?>";
        });

        Blueprint::macro('smallId', function ($column = 'id') {
            return $this->smallIncrements($column);
        });

        Blueprint::macro('smallForeignId', function (string $column): ForeignIdColumnDefinition {
            $this->columns[] = $column = new ForeignIdColumnDefinition($this, [
                'type' => 'smallInteger',
                'name' => $column,
                'autoIncrement' => false,
                'unsigned' => true,
            ]);

            return $column;
        });

        Blueprint::macro('smallForeignIdFor', function ($model, ?string $column = null): ForeignIdColumnDefinition {
            if (is_string($model)) {
                $model = new $model;
            }

            return $this->smallForeignId($column ?: $model->getForeignKey());
        });
    }
}
