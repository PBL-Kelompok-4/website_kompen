<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProdiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'id_prodi' => 1,
                'kode_prodi' => 'TI',
                'nama_prodi' => 'Teknik Informatika'
            ],
            [
                'id_prodi' => 2,
                'kode_prodi' => 'SIB',
                'nama_prodi' => 'Sistem Informasi Bisnis'
            ],
            [
                'id_prodi' => 3,
                'kode_prodi' => 'PPLS',
                'nama_prodi' => 'Pengembangan Piranti Lunak Situs'
            ]  
        ];

        DB::table('prodi')->insert($data);
    }
}
