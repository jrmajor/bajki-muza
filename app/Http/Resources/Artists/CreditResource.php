<?php

namespace App\Http\Resources\Artists;

use App\Http\Resources\Tales\CoverResource;
use App\Models\Tale;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property Tale $resource
 */
class CreditResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'slug' => $this->resource->slug,
            'title' => $this->resource->title,
            'year' => $this->resource->year,
            'cover' => new CoverResource($this->whenNotNull($this->resource->cover)),
        ];
    }
}
