<?php

namespace App\Enums\MCU;

enum OuterEarEnum: string
{
    case HEALTHY = 'healthy';
    case UNHEALTHY = 'unhealthy';

    public function label(): string
    {
        return match($this) {
            self::HEALTHY => 'Sehat',
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
            'healthy', 'sehat' => self::HEALTHY,
            default => null,
        };
    }

    public static function labels(): array
    {
        return [
            self::HEALTHY->value => self::HEALTHY->label(),
            self::UNHEALTHY->value => self::UNHEALTHY->label(),
        ];
    }
}