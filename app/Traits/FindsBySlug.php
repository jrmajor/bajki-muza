<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\ModelNotFoundException;

trait FindsBySlug
{
    /**
     * Find a model by its primary key.
     *
     * @param mixed $id
     * @param array $columns
     *
     * @return \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Collection|null
     */
    public static function findBySlug($id, $columns = ['*'])
    {
        $query = self::where('slug', '=', $id);

        return $query->first($columns);
    }

    /**
     * Find a model by its primary key or throw an exception.
     *
     * @param mixed $id
     * @param array $columns
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     *
     * @return \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Collection
     */
    public static function findBySlugOrFail($id, $columns = ['*'])
    {
        $result = self::findBySlug($id, $columns);

        if (!is_null($result)) {
            return $result;
        }

        throw (new ModelNotFoundException())->setModel(get_class($this->model));
    }
}
