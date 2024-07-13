<?php

namespace App\Http\Resources\Tales;

use App\Images\Cover;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property Cover $resource
 */
class CoverResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'placeholder' => $this->resource->placeholder(),
            'url' => collect(Cover::sizes())
                ->mapWithKeys(fn (int $size) => [$size => $this->resource->url($size)]),
        ];
    }
}
