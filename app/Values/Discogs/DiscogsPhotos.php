<?php

namespace App\Values\Discogs;

use Countable;
use Psl\Iter;
use Psl\Vec;

final class DiscogsPhotos implements Countable
{
    public function __construct(
        /** @var list<DiscogsPhoto> */
        private readonly array $items,
    ) { }

    public function primary(): ?DiscogsPhoto
    {
        return Iter\search($this->items, fn (DiscogsPhoto $p) => $p->primary);
    }

    /**
     * @return list<DiscogsPhoto>
     */
    public function secondary(): array
    {
        return Vec\filter($this->items, fn (DiscogsPhoto $p) => ! $p->primary);
    }

    /**
     * @return list<DiscogsPhoto>
     */
    public function all(): array
    {
        return $this->items;
    }

    public function count(): int
    {
        return count($this->items);
    }
}
