<?php

namespace App\Values;

use Spatie\Enum\Laravel\Enum;

/**
 * @method static self director()
 * @method static self lyricist()
 * @method static self composer()
 */
final class CreditType extends Enum
{
    protected static function labels(): array
    {
        return [
            'director' => 'reżyser',
            'lyricist' => 'słowa',
            'composer' => 'kompozytor',
        ];
    }
}
