<?php

namespace App\Images\Values;

use Illuminate\Contracts\Database\Eloquent\Castable;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Contracts\Support\Arrayable;
use Psl\Json;
use Psl\Type;

/**
 * @implements Arrayable<string, mixed>
 */
final class ArtistPhotoCrop implements Arrayable, Castable
{
    public function __construct(
        public readonly ArtistFaceCrop $face,
        public readonly ArtistImageCrop $image,
    ) { }

    /**
     * @param array{face: array<string, int>, image: array<string, int>} $crop
     */
    public static function fromArray(array $crop): self
    {
        return new self(
            new ArtistFaceCrop(...$crop['face']),
            new ArtistImageCrop(...$crop['image']),
        );
    }

    /**
     * @return array{face: array<string, int>, image: array<string, int>}
     */
    public function toArray(): array
    {
        return [
            'face' => $this->face->toArray(),
            'image' => $this->image->toArray(),
        ];
    }

    /**
     * @param array<never> $arguments
     *
     * @return CastsAttributes<self, self>
     */
    public static function castUsing(array $arguments): CastsAttributes
    {
        /**
         * @implements CastsAttributes<self, self>
         */
        return new class implements CastsAttributes {
            /**
             * @param array<string, mixed> $attributes
             */
            public function get($model, string $key, $value, array $attributes): ?ArtistPhotoCrop
            {
                return $value ? ArtistPhotoCrop::fromArray(Json\decode($value)) : null;
            }

            /**
             * @param array<string, mixed> $attributes
             */
            public function set($model, string $key, $value, array $attributes): string
            {
                $value = Type\instance_of(ArtistPhotoCrop::class)->coerce($value);

                return Json\encode($value->toArray());
            }
        };
    }

    public static function fake(): self
    {
        return new self(
            new ArtistFaceCrop(x: 181, y: 246, size: 189),
            new ArtistImageCrop(x: 79, y: 247, width: 529, height: 352),
        );
    }
}
