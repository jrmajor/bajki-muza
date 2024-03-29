<?php

namespace App\Values\Discogs;

final class DiscogsPhoto
{
    public function __construct(
        public readonly bool $primary,
        public readonly string $uri,
        public readonly string $thumbUri,
        public readonly int $width,
        public readonly int $height,
    ) { }
}
