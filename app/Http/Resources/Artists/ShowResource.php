<?php

namespace App\Http\Resources\Artists;

use App\Models\Artist;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Psl\Dict;

/**
 * @property Artist $resource
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
            'name' => $this->resource->name,
            'appearances' => $this->resource->appearances(),
            'photo' => new FullPhotoResource($this->whenNotNull($this->resource->photo)),
            'discogsUrl' => $this->resource->discogs_url,
            'discogsPhoto' => $this->resource->discogsPhoto(),
            'filmpolskiUrl' => $this->resource->filmpolski_url,
            'wikipediaUrl' => $this->resource->wikipedia_url,
            'wikipediaExtract' => $this->resource->wikipedia_extract,
            'asActor' => ActorResource::collection($this->resource->asActor),
            'credits' => Dict\map(
                $this->resource->orderedCredits(),
                fn ($group) => CreditResource::collection($group),
            ),
        ];
    }
}
