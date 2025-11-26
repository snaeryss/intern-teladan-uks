<?php

namespace App\Enums;

enum MonthEnum: string
{
    case January = 'Januari';
    case February = 'Februari';
    case March = 'Maret';
    case April = 'April';
    case May = 'Mei';
    case June = 'Juni';
    case July = 'Juli';
    case August = 'Agustus';
    case September = 'September';
    case October = 'Oktober';
    case November = 'November';
    case December = 'Desember';

    /**
     * Get all month values as array
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    /**
     * Get month order for sorting
     */
    public function order(): int
    {
        return match ($this) {
            self::January => 1,
            self::February => 2,
            self::March => 3,
            self::April => 4,
            self::May => 5,
            self::June => 6,
            self::July => 7,
            self::August => 8,
            self::September => 9,
            self::October => 10,
            self::November => 11,
            self::December => 12,
        };
    }

    /**
     * Get month name in Indonesian from month number
     */
    public static function fromNumber(int $monthNumber): string
    {
        return match($monthNumber) {
            1 => self::January->value,
            2 => self::February->value,
            3 => self::March->value,
            4 => self::April->value,
            5 => self::May->value,
            6 => self::June->value,
            7 => self::July->value,
            8 => self::August->value,
            9 => self::September->value,
            10 => self::October->value,
            11 => self::November->value,
            12 => self::December->value,
            default => throw new \InvalidArgumentException("Invalid month number: {$monthNumber}"),
        };
    }
}