<?php

namespace App\Enums\MCU;

enum AnemiaEnum: string
{
    case TIDAK = 'tidak';
    case YA = 'ya';

    public function label(): string
    {
        return match($this) {
            self::TIDAK => 'Tidak',
            self::YA => 'Ya',
        };
    }

    public static function fromLabel(?string $label): ?self
    {
        if (!$label) {
            return null; 
        }

        return match($label) {
            'Ya', 'ya' => self::YA,
            'Tidak', 'tidak' => self::TIDAK,
            default => null, 
        };
    }

    public static function labels(): array
    {
        return [
            self::TIDAK->value => self::TIDAK->label(),
            self::YA->value => self::YA->label(),
        ];
    }

    public function hasAnemia(): bool
    {
        return $this === self::YA;
    }
}