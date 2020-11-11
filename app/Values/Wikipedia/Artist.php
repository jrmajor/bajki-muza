<?php

namespace App\Values\Wikipedia;

use Illuminate\Support\Str;
use Spatie\DataTransferObject\DataTransferObject;

class Artist extends DataTransferObject
{
    public string $id;

    public string $name;

    public static function fromArray(array $artist): self
    {
        return new self([
            'id' => urldecode(Str::afterLast($artist['uri'], '/')),
            'name' => $artist['name'],
        ]);
    }
}
