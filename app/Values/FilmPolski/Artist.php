<?php

namespace App\Values\FilmPolski;

use Illuminate\Contracts\Support\Arrayable;

/**
 * @implements Arrayable<string, mixed>
 */
final readonly class Artist implements Arrayable
{
    public function __construct(
        public int $id,
        public string $name,
    ) { }

    public function toArray(): array
    {
        return ['id' => $this->id, 'name' => $this->name];
    }
}
