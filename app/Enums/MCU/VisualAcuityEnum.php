<?php

namespace App\Enums\MCU;

enum VisualAcuityEnum: string
{
    case NORMAL = 'normal';
    case LOW_VISION = 'low_vision';
    case BLINDNESS = 'blindness';
    case REFRACTIVE_DISORDER = 'refractive_disorder';

    public function label(): string
    {
        return match($this) {
            self::NORMAL => 'Normal',
            self::LOW_VISION => 'Low Vision',
            self::BLINDNESS => 'Kebutaan',
            self::REFRACTIVE_DISORDER => 'Kelainan Refraksi',
        };
    }

    public static function fromValue(?string $value): ?self
    {
        if (!$value) {
            return null;
        }

        return match(strtolower($value)) {
            'low_vision', 'lowvision' => self::LOW_VISION,
            'blindness', 'kebutaan' => self::BLINDNESS,
            'refractive_disorder', 'kelainan_refraksi' => self::REFRACTIVE_DISORDER,
            'normal' => self::NORMAL,
            default => null,
        };
    }

    public static function labels(): array
    {
        return [
            self::NORMAL->value => self::NORMAL->label(),
            self::LOW_VISION->value => self::LOW_VISION->label(),
            self::BLINDNESS->value => self::BLINDNESS->label(),
            self::REFRACTIVE_DISORDER->value => self::REFRACTIVE_DISORDER->label(),
        ];
    }
}