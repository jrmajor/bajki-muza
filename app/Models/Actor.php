<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class Actor extends Pivot
{
    protected $table = 'tales_actors';

    public $incrementing = true;

    protected $casts = [
        'credit_nr' => 'int',
    ];
}
