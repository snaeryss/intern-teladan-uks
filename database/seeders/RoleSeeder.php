<?php

namespace Database\Seeders;

use Spatie\Permission\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::firstOrCreate([
            'name' => 'SuperVisor',
            'description' => 'Mendapat semua hak akses untuk memantau jalannya program',
        ]);
        Role::firstOrCreate([
            'name' => 'Kepala UKS',
            'description' => 'Mendapat semua hak akses untuk memantau jalannya program',
        ]);
        Role::firstOrCreate([
            'name' => 'Perawat UKS',
            'description' => 'Mendapat hak akses untuk menjalankan program',
        ]);
        Role::firstOrCreate([
            'name' => 'Doktor',
            'description' => 'Mendapat hak akses untuk menjalankan program',
        ]);
        Role::firstOrCreate([
            'name' => 'Doktor Gigi',
            'description' => 'Mendapat hak akses untuk menjalankan program',
        ]);
        Role::firstOrCreate([
            'name' => 'Wali Kelas',
            'description' => 'Mendapat hak akses untuk menjalankan program',
        ]);
    }
}