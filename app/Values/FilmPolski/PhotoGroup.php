<?php

namespace App\Values\FilmPolski;

final class PhotoGroup
{
    public function __construct(
        public readonly ?string $title,
        public readonly ?int $year,
        /** @var list<string> */
        public readonly array $photos,
    ) { }
}
