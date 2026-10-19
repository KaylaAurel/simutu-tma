<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PTA;

class PTASeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        PTA::updateOrCreate(
            [
                'nama_pta' => 'Kayla Aurel Putri Prasetyo',
            ],
            [
                'nomor_whatsapp' => '6281555932835',
                'status' => 'aktif',
            ]
        );
    }
}