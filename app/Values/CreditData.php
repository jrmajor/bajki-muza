<?php

namespace App\Values;

use Illuminate\Contracts\Support\Arrayable;

/**
 * @implements Arrayable<string, mixed>
 */
final readonly class CreditData implements Arrayable
{
    public function __construct(
        public string $type,
        public ?string $as,
        public int $nr,
    ) { }

    public function toArray(): array
    {
        return ['type' => $this->type, 'as' => $this->as, 'nr' => $this->nr];
    }
}
