<?php

namespace App\Values;

use Spatie\DataTransferObject\DataTransferObject;

class CreditData extends DataTransferObject
{
    public string $type;

    public ?string $as;

    public int $nr;
}
