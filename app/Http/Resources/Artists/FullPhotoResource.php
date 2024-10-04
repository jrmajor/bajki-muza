<?php

namespace App\Http\Resources\Artists;

use App\Images\Photo;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property Photo $resource
 */
class FullPhotoResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'placeholder' => $this->resource->placeholder(),
            'width' => $this->resource->width,
            'height' => $this->resource->height,
            'url' => $this->resource->url(),
        ];
    }
}
