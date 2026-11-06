<?php

namespace App\Http\Resources\Tales;

use App\Models\Tale;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Psl\Dict;

/**
 * @property Tale $resource
 */
class ShowResource extends JsonResource
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
            'cover' => new FullCoverResource($this->whenNotNull($this->resource->cover)),
            'actors' => ActorResource::collection($this->resource->actors),
            'mainCredits' => Dict\map(
                $this->resource->mainCredits(),
                fn ($group) => CreditResource::collection($group),
            ),
            'customCredits' => Dict\map(
                $this->resource->customCredits(),
                fn ($group) => CreditResource::collection($group),
            ),
        ];
    }
}
