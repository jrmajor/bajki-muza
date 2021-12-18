<?php

namespace App\Values;

use Spatie\DataTransferObject\DataTransferObject;

class CreditData extends DataTransferObject
{
    public readonly string $type;

    public readonly ?string $as;

    public readonly int $nr;
}
