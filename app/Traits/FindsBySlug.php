<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\ModelNotFoundException;

trait FindsBySlug
{
    public static function findBySlug($id, $columns = ['*'])
    {
        $query = self::where('slug', '=', $id);

        return $query->first($columns);
    }

    public static function findBySlugOrFail($id, $columns = ['*'])
    {
        $result = self::findBySlug($id, $columns);

        if (!is_null($result)) {
            return $result;
        }

        throw (new ModelNotFoundException())->setModel(self::class);
    }
}
