<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ListKompetensiMahasiswaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'id_list_kompetensi_mahasiswa' => 1,
                'id_mahasiswa' => 1,
                'id_kompetensi' => 1
            ],
            [
                'id_list_kompetensi_mahasiswa' => 2,
                'id_mahasiswa' => 1,
                'id_kompetensi' => 2
            ],
            [
                'id_list_kompetensi_mahasiswa' => 3,
                'id_mahasiswa' => 2,
                'id_kompetensi' => 3
            ],
            [
                'id_list_kompetensi_mahasiswa' => 4,
                'id_mahasiswa' => 2,
                'id_kompetensi' => 4
            ],
            [
                'id_list_kompetensi_mahasiswa' => 5,
                'id_mahasiswa' => 3,
                'id_kompetensi' => 5
            ],
            [
                'id_list_kompetensi_mahasiswa' => 6,
                'id_mahasiswa' => 3,
                'id_kompetensi' => 6
            ],
            [
                'id_list_kompetensi_mahasiswa' => 7,
                'id_mahasiswa' => 4,
                'id_kompetensi' => 7
            ],
            [
                'id_list_kompetensi_mahasiswa' => 8,
                'id_mahasiswa' => 4,
                'id_kompetensi' => 8
            ],
            [
                'id_list_kompetensi_mahasiswa' => 9,
                'id_mahasiswa' => 5,
                'id_kompetensi' => 9
            ],
            [
                'id_list_kompetensi_mahasiswa' => 10,
                'id_mahasiswa' => 5,
                'id_kompetensi' => 10
            ],
            [
                'id_list_kompetensi_mahasiswa' => 11,
                'id_mahasiswa' => 6,
                'id_kompetensi' => 1
            ],
            [
                'id_list_kompetensi_mahasiswa' => 12,
                'id_mahasiswa' => 6,
                'id_kompetensi' => 2
            ],
            [
                'id_list_kompetensi_mahasiswa' => 13,
                'id_mahasiswa' => 7,
                'id_kompetensi' => 3
            ],
            [
                'id_list_kompetensi_mahasiswa' => 14,
                'id_mahasiswa' => 7,
                'id_kompetensi' => 4
            ],
            [
                'id_list_kompetensi_mahasiswa' => 15,
                'id_mahasiswa' => 8,
                'id_kompetensi' => 5
            ],
            [
                'id_list_kompetensi_mahasiswa' => 16,
                'id_mahasiswa' => 8,
                'id_kompetensi' => 6
            ],
            [
                'id_list_kompetensi_mahasiswa' => 17,
                'id_mahasiswa' => 9,
                'id_kompetensi' => 7
            ],
            [
                'id_list_kompetensi_mahasiswa' => 18,
                'id_mahasiswa' => 9,
                'id_kompetensi' => 8
            ],
            [
                'id_list_kompetensi_mahasiswa' => 19,
                'id_mahasiswa' => 10,
                'id_kompetensi' => 9
            ],
            [
                'id_list_kompetensi_mahasiswa' => 20,
                'id_mahasiswa' => 10,
                'id_kompetensi' => 10
            ],
        ];

        DB::table('list_kompetensi_mahasiswa')->insert($data);
    }
}
