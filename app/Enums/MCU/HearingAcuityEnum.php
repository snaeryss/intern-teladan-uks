<?php

namespace App\Enums\MCU;

enum HearingAcuityEnum: string
{
    case NORMAL = 'normal';
    case IMPAIRED = 'impaired';

    public function label(): string
    {
        return match($this) {
            self::NORMAL => 'Normal',
            self::IMPAIRED => 'Ada Gangguan',
        };
    }

    public static function fromValue(?string $value): ?self
    {
        if (!$value) {
            return null;
        }

        return match(strtolower($value)) {
            'impaired', 'gangguan', 'ada gangguan' => self::IMPAIRED,
            'normal' => self::NORMAL,
            default => null,
        };
    }

    public static function labels(): array
    {
        return [
            self::NORMAL->value => self::NORMAL->label(),
            self::IMPAIRED->value => self::IMPAIRED->label(),
        ];
    }
}