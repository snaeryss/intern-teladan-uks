<?php

namespace App\Enums\Student;

enum Level: string
{
    case DC = '00';
    case KB = '11';
    case TK = '22';
    case SD = '33';
    case SMP = '44';
    case SMA = '55';

    /**
     * Get short level name (DC, KB, TK, SD, SMP, SMA) - untuk display
     */
    public function getShortName(): string
    {
        return match($this) {
            self::DC => 'DC',
            self::KB => 'KB',
            self::TK => 'TK',
            self::SD => 'SD',
            self::SMP => 'SMP',
            self::SMA => 'SMA',
        };
    }

    /**
     * Get group level code (dctk, sd, smp, sma) - untuk routing/form
     */
    public function getGroupLevel(): string
    {
        return match($this) {
            self::DC, self::KB, self::TK => 'dctk',
            self::SD => 'sd',
            self::SMP => 'smp',
            self::SMA => 'sma',
        };
    }

    /**
     * Get full level name in Indonesian
     */
    public function getFullName(): string
    {
        return match($this) {
            self::DC, self::KB, self::TK => 'Day Care / Kelompok Bermain / TK',
            self::SD => 'Sekolah Dasar',
            self::SMP => 'Sekolah Menengah Pertama',
            self::SMA => 'Sekolah Menengah Atas',
        };
    }
}