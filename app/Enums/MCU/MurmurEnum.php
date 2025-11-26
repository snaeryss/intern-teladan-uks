<?php

namespace App\Enums\MCU;

enum MurmurEnum: string
{
    case NO = 'no';
    case YES = 'yes';

    public function label(): string
    {
        return match($this) {
            self::NO => 'Tidak',
            self::YES => 'Ya',
        };
    }

    public static function fromValue(?string $value): ?self
    {
        if (!$value) {
            return null;
        }

        return match(strtolower($value)) {
            'yes', 'ya' => self::YES,
            'no', 'tidak' => self::NO,
            default => null,
        };
    }

    public static function labels(): array
    {
        return [
            self::NO->value => self::NO->label(),
            self::YES->value => self::YES->label(),
        ];
    }

    public function hasMurmur(): bool
    {
        return $this === self::YES;
    }
}