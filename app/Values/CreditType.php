<?php

namespace App\Values;

use Spatie\Enum\Laravel\Enum;

/**
 * @method static self directing()
 * @method static self text()
 * @method static self music()
 */
final class CreditType extends Enum
{
    protected static function labels(): array
    {
        return [
            'directing' => 'reżyser',
            'text' => 'słowa',
            'music' => 'muzyka',
        ];
    }
}
