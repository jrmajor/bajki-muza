<?php

namespace App\Values;

use Spatie\Enum\Laravel\Enum;

use function Safe\array_flip;

/**
 * @method static self text()
 * @method static self author()
 * @method static self lyrics()
 * @method static self adaptation()
 * @method static self translation()
 * @method static self music()
 * @method static self arrangement()
 * @method static self directing()
 * @method static self directors_assistant()
 * @method static self production()
 * @method static self producers_assistant()
 * @method static self recording_director()
 * @method static self sound_operator()
 * @method static self sound_production()
 * @method static self editor()
 * @method static self production_manager()
 * @method static self artwork()
 */
final class CreditType extends Enum
{
    protected static array $basic = [
        'text', 'author',
        'lyrics', 'music',
    ];

    protected static function labels(): array
    {
        return [
            'text' => 'słowa',
            'author' => 'autor',
            'lyrics' => 'teksty piosenek',
            'adaptation' => 'adaptacja',
            'translation' => 'przekład',
            'music' => 'muzyka',
            'arrangement' => 'aranżacja',

            'directing' => 'reżyseria',
            'directors_assistant' => 'asystent reżysera',
            'production' => 'realizacja',
            'producers_assistant' => 'asystent realizatora',
            'recording_director' => 'reżyser nagrania',
            'sound_operator' => 'operator dźwięku',
            'sound_production' => 'realizacja dźwięku',
            'editor' => 'redaktor',
            'production_manager' => 'kierownik produkcji',
            'artwork' => 'opracowanie graficzne',
        ];
    }

    public function order(): int
    {
        return array_flip(array_keys(self::labels()))[$this->value];
    }

    public function isCustom(): bool
    {
        return ! in_array($this->value, self::$basic);
    }
}
