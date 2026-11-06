<?php

namespace App\Values\Discogs;

final readonly class DiscogsPhoto
{
    public function __construct(
        public bool $primary,
        public string $uri,
        public string $thumbUri,
        public int $width,
        public int $height,
    ) { }
}
