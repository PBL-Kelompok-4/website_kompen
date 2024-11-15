<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class JenisKompenSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'id_jenis_kompen' => 1,
                'kode_jenis' => 'PNLT',
                'nama_jenis' => 'Penelitian'
            ],
            [
                'id_jenis_kompen' => 2,
                'kode_jenis' => 'PNGB',
                'nama_jenis' => 'Pengabdian'
            ],
            [
                'id_jenis_kompen' => 3,
                'kode_jenis' => 'TKNS',
                'nama_jenis' => 'Teknis'
            ],
            [
                'id_jenis_kompen' => 4,
                'kode_jenis' => 'ADMN',
                'nama_jenis' => 'Administratif'
            ]
        ];

        DB::table('jenis_kompen')->insert($data);
    }
}
