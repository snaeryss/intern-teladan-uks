<?php

namespace App\Enums\Student;

enum Gender: string
{
    case MALE = 'L';
    case FEMALE = 'P';
    case UNKNOWN = '-';

    /**
     * Mengembalikan label yang bisa dibaca oleh manusia.
     *
     * @return string
     */
    public function label(): string
    {
        return match ($this) {
            self::MALE => 'Laki-laki',
            self::FEMALE => 'Perempuan',
            self::UNKNOWN => 'Tidak Diketahui',
        };
    }
}