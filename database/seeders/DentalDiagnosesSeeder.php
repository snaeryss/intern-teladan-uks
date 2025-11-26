<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DentalDiagnosesSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();
        
        $diagnoses = [
            ['code' => 'Sou', 'description' => 'Gigi Sehat, Normal, tanpa Kelainan', 'created_at' => $now, 'updated_at' => $now],
            ['code' => 'Car', 'description' => 'Caries', 'created_at' => $now, 'updated_at' => $now],
            ['code' => 'Nvt', 'description' => 'Gigi Non Vital', 'created_at' => $now, 'updated_at' => $now],
            ['code' => 'Cfr', 'description' => 'Crown Fracture / Fraktur Mahkota', 'created_at' => $now, 'updated_at' => $now],
            ['code' => 'Rrx', 'description' => 'Sisa Akar', 'created_at' => $now, 'updated_at' => $now],
            ['code' => 'Mis', 'description' => 'Gigi Hilang', 'created_at' => $now, 'updated_at' => $now],
            ['code' => 'Non', 'description' => 'Gigi Tidak ada/tidak diketahui', 'created_at' => $now, 'updated_at' => $now],
            ['code' => 'Une', 'description' => 'Un-erupted', 'created_at' => $now, 'updated_at' => $now],
            ['code' => 'Pre', 'description' => 'Partial Erupted', 'created_at' => $now, 'updated_at' => $now],
            ['code' => 'Imv', 'description' => 'Impacted Visible', 'created_at' => $now, 'updated_at' => $now],
            ['code' => 'Ano', 'description' => 'Anomali', 'created_at' => $now, 'updated_at' => $now],
            ['code' => 'Dia', 'description' => 'Diastema', 'created_at' => $now, 'updated_at' => $now],
            ['code' => 'Att', 'description' => 'Atrisi', 'created_at' => $now, 'updated_at' => $now],
            ['code' => 'Abr', 'description' => 'Abarasi', 'created_at' => $now, 'updated_at' => $now],
            ['code' => 'rdx', 'description' => 'Radixies', 'created_at' => $now, 'updated_at' => $now],
            ['code' => 'Ram', 'description' => 'Rampan Karies', 'created_at' => $now, 'updated_at' => $now],
            ['code' => 'Per', 'description' => 'Persistensi', 'created_at' => $now, 'updated_at' => $now],
            ['code' => 'Malo', 'description' => 'Maloklusi / Malposisi Gigi', 'created_at' => $now, 'updated_at' => $now],
            ['code' => 'Gigi', 'description' => 'Gigi Decidui Depan', 'created_at' => $now, 'updated_at' => $now],
        ];

        DB::table('dental_diagnoses')->insert($diagnoses);
    }
}