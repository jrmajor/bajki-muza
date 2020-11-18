<?php

namespace App\Values;

use Spatie\Enum\Laravel\Enum;

/**
 * @method static self text()
 * @method static self author()
 * @method static self music()
 * @method static self directing()
 */
final class CreditType extends Enum
{
    protected static function labels(): array
    {
        return [
            'text' => 'słowa',
            'author' => 'autor',
            'music' => 'muzyka',
            'directing' => 'reżyseria',
        ];
    }
}
