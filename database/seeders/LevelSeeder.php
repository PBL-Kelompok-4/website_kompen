<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LevelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'id_level' => 1,
                'kode_level' => 'ADM',
                'nama_level' => 'Admin'
            ],
            [
                'id_level' => 2,
                'kode_level' => 'DSN',
                'nama_level' => 'Dosen'
            ],
            [
                'id_level' => 3,
                'kode_level' => 'TDK',
                'nama_level' => 'Tendik'
            ],
            [
                'id_level' => 4,
                'kode_level' => 'MHS',
                'nama_level' => 'Mahasiswa'
            ],
        ];

        DB::table('level')->insert($data);
    }
}
