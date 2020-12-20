<?php

namespace App\Images;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use ReflectionClass;

class ImageCast implements CastsAttributes
{
    public function __construct(
        protected string $class,
    ) { }

    public function get($model, string $key, $value, array $attributes)
    {
        if (is_null($value)) {
            return null;
        }

        $reflection = new ReflectionClass($this->class);

        return $reflection->newInstance($value);
    }

    public function set($model, string $key, $value, array $attributes)
    {
        if (is_null($value)) {
            return null;
        }

        return $value instanceof $this->class
            ? $value->filename()
            : (string) $value;
    }
}
