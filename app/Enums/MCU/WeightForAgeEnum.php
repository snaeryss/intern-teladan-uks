<?php

namespace App\Enums\MCU; 

enum WeightForAgeEnum: string
{
    case NORMAL = 'normal';
    case GIZI_KURANG = 'gizi_kurang';
    case GIZI_LEBIH = 'gizi_lebih';

    public function label(): string
    {
        return match($this) {
            self::NORMAL => 'Normal',
            self::GIZI_KURANG => 'Gizi Kurang',
            self::GIZI_LEBIH => 'Gizi Lebih',
        };
    }

    public static function fromLabel(?string $label): ?self
    {
        if (!$label) {
            return null;
        }

        return match($label) {
            'Normal', 'normal' => self::NORMAL,
            'Gizi Kurang', 'gizi_kurang' => self::GIZI_KURANG,
            'Gizi Lebih', 'gizi_lebih' => self::GIZI_LEBIH,
            default => null, 
        };
    }

    public static function labels(): array
    {
        return [
            self::NORMAL->value => self::NORMAL->label(),
            self::GIZI_KURANG->value => self::GIZI_KURANG->label(),
            self::GIZI_LEBIH->value => self::GIZI_LEBIH->label(),
        ];
    }
}