<?php

namespace App\Enums\MCU;

enum HygieneStatusEnum: string
{
    case HEALTHY = 'healthy';
    case UNHEALTHY = 'unhealthy';

    public function label(): string
    {
        return match($this) {
            self::HEALTHY => 'Sehat/Bersih',
            self::UNHEALTHY => 'Tidak Sehat/Kotor',
        };
    }

    public static function fromValue(?string $value): ?self
    {
        if (!$value) {
            return null;
        }

        return match(strtolower($value)) {
            'unhealthy', 'tidak', 'tidak sehat', 'kotor' => self::UNHEALTHY,
            'healthy', 'sehat', 'bersih' => self::HEALTHY,
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