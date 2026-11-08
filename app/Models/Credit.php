<?php

namespace App\Models;

use App\Values\CreditType;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Relations\Pivot;

/**
 * @property ?string $as
 */
#[Table('credits', incrementing: true)]
class Credit extends Pivot
{
    protected function casts(): array
    {
        return [
            'type' => CreditType::class,
            'nr' => 'int',
        ];
    }

    public function ofType(CreditType $type): bool
    {
        return $this->type === $type;
    }

    public function isCustom(): bool
    {
        return $this->type->isCustom();
    }
}
