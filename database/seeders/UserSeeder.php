<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['username' => 'admin.tma'],
            [
                'name' => 'Administrator TMA',
                'password' => Hash::make('AdminTMA123!'),
                'role' => 'admin_tma',
            ]
        );

        User::updateOrCreate(
            ['username' => 'asman.tma'],
            [
                'name' => 'Asman TMA',
                'password' => Hash::make('AsmanTMA123!'),
                'role' => 'asman_tma',
            ]
        );
    }
}