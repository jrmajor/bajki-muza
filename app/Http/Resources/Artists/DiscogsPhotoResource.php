<?php

namespace App\Http\Resources\Artists;

use App\Values\Discogs\DiscogsPhoto;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property DiscogsPhoto $resource
 */
class DiscogsPhotoResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'uri' => $this->resource->uri,
            'width' => $this->resource->width,
            'height' => $this->resource->height,
        ];
    }
}
