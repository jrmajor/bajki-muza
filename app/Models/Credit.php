<?php

namespace App\Models;

use App\Values\CreditType;
use Illuminate\Database\Eloquent\Relations\Pivot;

class Credit extends Pivot
{
    public $incrementing = true;

    protected $casts = [
        'type' => CreditType::class,
        'nr' => 'int',
    ];

    public function ofType(CreditType $type): bool
    {
        return $this->type->equals($type);
    }
}
