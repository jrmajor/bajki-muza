<?php

namespace App\Values;

use Spatie\DataTransferObject\Attributes\Strict;
use Spatie\DataTransferObject\DataTransferObject;

#[Strict]
class CreditData extends DataTransferObject
{
    public string $type;

    public ?string $as;

    public int $nr;
}
