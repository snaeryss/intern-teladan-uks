<?php

namespace App\Enums;

enum CheckUpTypeEnum : string
{
    case Screening = 'SCR';
    case MedicalCheckUp = 'MCU';
    case DentalCheckUp = 'DCU';

    public function label(): string
    {
        return match ($this) {
            self::Screening => 'General Screening',
            self::MedicalCheckUp => 'Medical Check Up',
            self::DentalCheckUp => 'Dental Check Up'
        };
    }
}