<?php

namespace App\Http\Resources\Tales;

use App\Http\Resources\Artists\FacePhotoResource;
use App\Models\Artist;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Psl\Type;

/**
 * @property Artist $resource
 */
class ActorResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'characters' => $this->resource->credit->characters !== null
                ? explode('; ', $this->resource->credit->characters)
                : null,
            'slug' => $this->resource->slug,
            'name' => $this->resource->name,
            'appearances' => Type\int()->coerce($this->resource->appearances),
            'photo' => new FacePhotoResource($this->whenNotNull($this->resource->photo)),
            'discogsPhotoThumb' => $this->resource->photo ? null : $this->resource->discogsPhoto('thumb'),
        ];
    }
}
