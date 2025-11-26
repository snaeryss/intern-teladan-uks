<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Period;
use App\Models\AcademicYear;

class PeriodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $activeAcademicYear = AcademicYear::where('is_active', true)->first();

        if ($activeAcademicYear) {
            Period::create([
                'name' => 'SCR',
                'month' => 'Juli',
                'year' => $activeAcademicYear->year_start,
                'is_active' => true,
                'academic_year_id' => $activeAcademicYear->id,
            ]);

            Period::create([
                'name' => 'DCU',
                'month' => 'Oktober',
                'year' => $activeAcademicYear->year_start,
                'is_active' => true,
                'academic_year_id' => $activeAcademicYear->id,
            ]);

            Period::create([
                'name' => 'MCU',
                'month' => 'Oktober',
                'year' => $activeAcademicYear->year_start,
                'is_active' => true,
                'academic_year_id' => $activeAcademicYear->id,
            ]);
        }
    }
}
