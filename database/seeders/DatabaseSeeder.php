<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['username' => 'admin'],
            [
                'name' => 'Pembimbing Magang',
                'email' => 'admin@plnicoplus.local',
                'password' => Hash::make('GantiPasswordIni123'),
            ]
        );
    }
}