<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Relations\Pivot;

/**
 * @property ?string $characters
 */
#[Table('tales_actors', incrementing: true)]
class Actor extends Pivot
{
    protected function casts(): array
    {
        return [
            'credit_nr' => 'int',
        ];
    }
}
