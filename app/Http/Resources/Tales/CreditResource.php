<?php

namespace App\Http\Resources\Tales;

use App\Models\Artist;
use App\Values\CreditType;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Psl\Type;

/**
 * @property Artist $resource
 */
class CreditResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $data = [
            'as' => $this->resource->credit->as,
            'slug' => $this->resource->slug,
            'name' => $this->resource->name,
            'appearances' => Type\int()->coerce($this->resource->appearances),
        ];

        if ($this->resource->credit->type === CreditType::Author) {
            $data['genetivus'] = $this->resource->genetivus;
        }

        return $data;
    }
}
