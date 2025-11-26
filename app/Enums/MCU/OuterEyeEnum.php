<?php

namespace App\Enums\MCU;

enum OuterEyeEnum: string
{
    case NORMAL = 'normal';
    case UNHEALTHY = 'unhealthy';

    public function label(): string
    {
        return match($this) {
            self::NORMAL => 'Normal',
            self::UNHEALTHY => 'Tidak Sehat',
        };
    }

    public static function fromValue(?string $value): ?self
    {
        if (!$value) {
            return null;
        }

        return match(strtolower($value)) {
            'unhealthy', 'tidak', 'tidak sehat' => self::UNHEALTHY,
            'normal' => self::NORMAL,
            default => null,
        };
    }

    public static function labels(): array
    {
        return [
            self::NORMAL->value => self::NORMAL->label(),
            self::UNHEALTHY->value => self::UNHEALTHY->label(),
        ];
    }
}