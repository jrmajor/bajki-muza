<?php

namespace App\Values;

use Illuminate\Contracts\Support\Arrayable;

final class CreditData implements Arrayable
{
    public function __construct(
        public readonly string $type,
        public readonly ?string $as,
        public readonly int $nr,
    ) { }

    public function toArray(): array
    {
        return ['type' => $this->type, 'as' => $this->as, 'nr' => $this->nr];
    }
}
