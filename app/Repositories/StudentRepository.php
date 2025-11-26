<?php

namespace App\Repositories;

use App\Enums\Student\Level;
use App\Models\Student;

class StudentRepository
{
    /**
     * Get detailed level name in Indonesian
     * 
     * @param Student $student
     * @return string
     */
    public function getLevelName(Student $student): string
    {
        return match ($student->school_level) {
            Level::DC, Level::KB, Level::TK => 'Day Care / Kelompok Bermain / TK',
            Level::SD => 'Sekolah Dasar',
            Level::SMP => 'Sekolah Menengah Pertama',
            Level::SMA => 'Sekolah Menengah Atas',
            default => 'Tidak Diketahui',
        };
    }

    /**
     * Get short level code for template/system purposes
     * 
     * @param Student $student
     * @return string
     */
    public function getLevelCode(Student $student): string
    {
        return match ($student->school_level) {
            Level::DC, Level::KB, Level::TK => 'dctk',
            Level::SD => 'sd',
            Level::SMP => 'smp',
            Level::SMA => 'sma',
            default => '',
        };
    }

    /**
     * Get level information sebagai array
     * 
     * @param Student $student
     * @return array
     */
    public function getLevelInfo(Student $student): array
    {
        return [
            'name' => $this->getLevelName($student),
            'code' => $this->getLevelCode($student),
            'enum' => $student->school_level,
        ];
    }

    /**
     * Get current class dari student
     * 
     * @param Student $student
     * @return mixed
     */
    public function getCurrentClass(Student $student)
    {
        return $student->studentClasses()->latest('group_year')->first();
    }

    /**
     * Get grouped levels untuk dropdown (static method)
     * 
     * @return array
     */
    public static function getGroupedLevels(): array
    {
        return [
            [
                'code' => 'dctk',
                'name' => 'DC / KB / TK',
                'enums' => [Level::DC, Level::KB, Level::TK]
            ],
            [
                'code' => 'sd',
                'name' => 'Sekolah Dasar (SD)',
                'enums' => [Level::SD]
            ],
            [
                'code' => 'smp',
                'name' => 'Sekolah Menengah Pertama (SMP)',
                'enums' => [Level::SMP]
            ],
            [
                'code' => 'sma',
                'name' => 'Sekolah Menengah Atas (SMA)',
                'enums' => [Level::SMA]
            ],
        ];
    }

    /**
     * Get level name by code (static method)
     * 
     * @param string $code
     * @return string|null
     */
    public static function getLevelNameByCode(string $code): ?string
    {
        $levels = self::getGroupedLevels();
        foreach ($levels as $level) {
            if ($level['code'] === $code) {
                return $level['name'];
            }
        }
        return null;
    }
}