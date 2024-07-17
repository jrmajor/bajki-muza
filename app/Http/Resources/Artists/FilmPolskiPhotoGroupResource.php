<?php

namespace App\Http\Resources\Artists;

use App\Values\FilmPolski\PhotoGroup;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property PhotoGroup $resource
 */
class FilmPolskiPhotoGroupResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'title' => $this->resource->title,
            'year' => $this->resource->year,
            'photos' => $this->resource->photos,
        ];
    }
}
