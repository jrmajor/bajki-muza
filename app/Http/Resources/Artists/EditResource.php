<?php

namespace App\Http\Resources\Artists;

use App\Models\Artist;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property Artist $resource
 */
class EditResource extends JsonResource
{
    public static $wrap = null;

    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'slug' => $this->resource->slug,
            'name' => $this->resource->name,
            'genetivus' => $this->resource->genetivus,
            'discogs' => $this->resource->discogs,
            'filmpolski' => $this->resource->filmpolski,
            'wikipedia' => $this->resource->wikipedia,
            'photo' => new EditPhotoResource($this->whenNotNull($this->resource->photo)),
            'discogsPhotos' => DiscogsPhotoResource::collection(
                $this->resource->discogsPhotos()->all(),
            ),
            'filmPolskiPhotos' => FilmPolskiPhotoGroupResource::collection(
                $this->resource->filmPolskiPhotos(),
            ),
        ];
    }
}
