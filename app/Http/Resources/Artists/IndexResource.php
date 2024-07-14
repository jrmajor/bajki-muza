<?php

namespace App\Http\Resources\Artists;

use App\Models\Artist;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property Artist $resource
 */
class IndexResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'slug' => $this->resource->slug,
            'name' => $this->resource->name,
            'appearances' => $this->resource->appearances,
            'photo' => new PhotoResource($this->whenNotNull($this->resource->photo)),
            'discogsPhotoThumb' => $this->resource->photo ? null : $this->resource->discogsPhoto('thumb'),
        ];
    }
}
