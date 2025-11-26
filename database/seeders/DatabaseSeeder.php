<?php

namespace Database\Seeders;

use App\Models\AcademicYear;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            AcademicYearSeeder::class,
            RoleSeeder::class,
            UserSeeder::class,
            PeriodSeeder::class,
        ]);
    }
}
