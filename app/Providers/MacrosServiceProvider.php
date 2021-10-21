<?php

namespace App\Providers;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Schema\ForeignIdColumnDefinition;
use Illuminate\Support\Collection;
use Illuminate\Support\ServiceProvider;

class MacrosServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Collection::macro('combineKeys', function ($keys) {
            return new static(array_combine($this->getArrayableItems($keys), $this->all()));
        });

        Blueprint::macro('smallId', function (string $column = 'id') {
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

        Blueprint::macro(
            'smallForeignIdFor',
            function ($model, ?string $column = null): ForeignIdColumnDefinition {
                if (is_string($model)) {
                    $model = new $model();
                }

                return $this->smallForeignId($column ?: $model->getForeignKey());
            },
        );
    }
}
