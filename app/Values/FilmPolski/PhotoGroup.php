<?php

namespace App\Values\FilmPolski;

final readonly class PhotoGroup
{
    public function __construct(
        public ?string $title,
        public ?int $year,
        /** @var list<string> */
        public array $photos,
    ) { }
}
