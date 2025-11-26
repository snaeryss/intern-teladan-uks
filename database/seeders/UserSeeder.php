<?php

namespace Database\Seeders;

use App\Enums\AccountTypeEnum;
use App\Models\User;

use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::firstOrCreate(
            ['username' => 'KS00100'],
            [
                'name' => 'TIM IT',
                'type' => AccountTypeEnum::Admin,
                'password' => bcrypt('123123'),
            ]
        );
        if (!$user->hasRole('SuperVisor')) {
            $user->assignRole('SuperVisor');
        }

        $user = User::firstOrCreate(
            ['username' => 'KSD0100'],
            [
                'name' => 'Doktor (Test)',
                'type' => AccountTypeEnum::Doctor,
                'password' => bcrypt('123123'),
            ]
        );
        if (!$user->hasRole('Doktor')) {
            $user->assignRole('Doktor');
        }

        $user = User::firstOrCreate(
            ['username' => 'KSD0200'],
            [
                'name' => 'Doktor Gigi (Test)',
                'type' => AccountTypeEnum::Doctor,
                'password' => bcrypt('123123'),
            ]
        );
        if (!$user->hasRole('Doktor Gigi')) {
            $user->assignRole('Doktor Gigi');
        }

        $user = User::firstOrCreate(
            ['username' => 'KSN0100'],
            [
                'name' => 'Perawat (Test)',
                'type' => AccountTypeEnum::Nurse,
                'password' => bcrypt('123123'),
            ]
        );
        if (!$user->hasRole('Perawat UKS')) {
            $user->assignRole('Perawat UKS');
        }
    }
}