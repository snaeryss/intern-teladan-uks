<?php

namespace App\Enums\MCU;

enum NailsStatusEnum: string
{
    case HEALTHY = 'healthy';
    case DIRTY = 'dirty';

    public function label(): string
    {
        return match($this) {
            self::HEALTHY => 'Sehat/Bersih',
            self::DIRTY => 'Kotor/Panjang',
        };
    }

    public static function fromValue(?string $value): ?self
    {
        if (!$value) {
            return null;
        }

        return match(strtolower($value)) {
            'dirty', 'kotor', 'kotor/panjang' => self::DIRTY,
            'healthy', 'sehat', 'bersih', 'sehat/bersih' => self::HEALTHY,
            default => null,
        };
    }

    public static function labels(): array
    {
        return [
            self::HEALTHY->value => self::HEALTHY->label(),
            self::DIRTY->value => self::DIRTY->label(),
        ];
    }
}