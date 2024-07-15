<?php

namespace App\Http\Resources\Tales;

use App\Models\Tale;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property Tale $resource
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
            'title' => $this->resource->title,
            'year' => $this->resource->year,
            'nr' => $this->resource->nr,
            'discogs' => $this->resource->discogs,
            'notes' => $this->resource->notes,
            'cover' => new CoverResource($this->whenNotNull($this->resource->cover)),
            'credits' => $this->resource->orderedCredits()->map(fn ($artist) => [
                'artist' => $artist->name,
                'type' => $artist->credit->type,
                'as' => $artist->credit->as,
                'nr' => $artist->credit->nr,
            ]),
            'actors' => $this->resource->actors->map(fn ($actor) => [
                'artist' => $actor->name,
                'characters' => $actor->credit->characters,
            ]),
        ];
    }
}
