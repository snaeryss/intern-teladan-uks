<?php

namespace App\Enums\MCU; 

enum HeightForAgeEnum: string
{
    case NORMAL = 'normal';
    case PENDEK = 'pendek';

    public function label(): string
    {
        return match($this) {
            self::NORMAL => 'Normal',
            self::PENDEK => 'Pendek',
        };
    }

    public static function fromLabel(?string $label): ?self
    {
        if (!$label) {
            return null;
        }

        return match($label) {
            'Normal', 'normal' => self::NORMAL,
            'Pendek', 'pendek' => self::PENDEK,
            default => null, 
        };
    }

    public static function labels(): array
    {
        return [
            self::NORMAL->value => self::NORMAL->label(),
            self::PENDEK->value => self::PENDEK->label(),
        ];
    }
}