<?php

namespace App\Enums\MCU; 

enum NutritionalStatusEnum: string
{
    case VERY_THIN = 'very_thin';
    case THIN = 'thin';
    case NORMAL = 'normal';
    case OVERWEIGHT = 'overweight';
    case VERY_OVERWEIGHT = 'very_overweight';

    public function label(): string
    {
        return match($this) {
            self::VERY_THIN => 'Sangat Kurus',
            self::THIN => 'Kurus',
            self::NORMAL => 'Normal',
            self::OVERWEIGHT => 'Gemuk',
            self::VERY_OVERWEIGHT => 'Sangat Gemuk (Obesitas)',
        };
    }

    public static function fromLabel(?string $label): ?self
    {
        if (!$label) {
            return null;
        }

        return match($label) {
            'Sangat Kurus' => self::VERY_THIN,
            'Kurus' => self::THIN,
            'Normal' => self::NORMAL,
            'Gemuk' => self::OVERWEIGHT,
            'Sangat Gemuk (Obesitas)', 'Obesitas' => self::VERY_OVERWEIGHT,
            default => null,
        };
    }

    public static function labels(): array
    {
        return [
            self::VERY_THIN->value => self::VERY_THIN->label(),
            self::THIN->value => self::THIN->label(),
            self::NORMAL->value => self::NORMAL->label(),
            self::OVERWEIGHT->value => self::OVERWEIGHT->label(),
            self::VERY_OVERWEIGHT->value => self::VERY_OVERWEIGHT->label(),
        ];
    }
}