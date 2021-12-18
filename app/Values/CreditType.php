<?php

namespace App\Values;

enum CreditType: string
{
    case Text = 'text';
    case Author = 'author';
    case Lyrics = 'lyrics';
    case Adaptation = 'adaptation';
    case Translation = 'translation';
    case Music = 'music';
    case Arrangement = 'arrangement';
    case Directing = 'directing';
    case DirectorsAssistant = 'directors_assistant';
    case Production = 'production';
    case ProducersAssistant = 'producers_assistant';
    case RecordingDirector = 'recording_director';
    case SoundOperator = 'sound_operator';
    case SoundProduction = 'sound_production';
    case Editor = 'editor';
    case ProductionManager = 'production_manager';
    case Artwork = 'artwork';

    public function order(): int
    {
        return array_search($this, self::cases());
    }

    public function isCustom(): bool
    {
        return ! in_array($this, [self::Text, self::Author, self::Lyrics, self::Music]);
    }

    public function label(): string
    {
        return match ($this) {
            self::Text => 'słowa',
            self::Author => 'autor',
            self::Lyrics => 'teksty piosenek',
            self::Adaptation => 'adaptacja',
            self::Translation => 'przekład',
            self::Music => 'muzyka',
            self::Arrangement => 'aranżacja',
            self::Directing => 'reżyseria',
            self::DirectorsAssistant => 'asystent reżysera',
            self::Production => 'realizacja',
            self::ProducersAssistant => 'asystent realizatora',
            self::RecordingDirector => 'reżyser nagrania',
            self::SoundOperator => 'operator dźwięku',
            self::SoundProduction => 'realizacja dźwięku',
            self::Editor => 'redaktor',
            self::ProductionManager => 'kierownik produkcji',
            self::Artwork => 'opracowanie graficzne',
        };
    }
}
