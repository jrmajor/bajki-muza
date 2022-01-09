<?php

namespace App\Values\FilmPolski;

use Illuminate\Contracts\Support\Arrayable;

class Artist implements Arrayable
{
    public function __construct(
        public readonly int $id,
        public readonly string $name,
    ) { }

    public function toArray(): array
    {
        return ['id' => $this->id, 'name' => $this->name];
    }
}
