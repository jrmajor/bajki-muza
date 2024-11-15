<?php

namespace App\Values\Discogs;

use Illuminate\Contracts\Support\Arrayable;

/**
 * @implements Arrayable<string, mixed>
 */
final class Artist implements Arrayable
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
