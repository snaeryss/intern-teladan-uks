<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AcademicYear;

class AcademicYearSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
      AcademicYear::create([
            'year_start' => '2025',
            'year_end'   => '2026',
            'is_active'  => true,
        ]);

        AcademicYear::create([
            'year_start' => '2024',
            'year_end'   => '2025',
            'is_active'  => false,
        ]);
    }
}