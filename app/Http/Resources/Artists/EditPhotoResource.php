<?php

namespace App\Http\Resources\Artists;

use App\Images\Photo;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property Photo $resource
 */
class EditPhotoResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'url' => $this->resource->originalUrl(),
            'source' => $this->resource->source,
            'crop' => $this->resource->crop(),
            'grayscale' => $this->resource->grayscale,
        ];
    }
}
