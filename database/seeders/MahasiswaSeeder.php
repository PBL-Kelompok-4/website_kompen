<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class MahasiswaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'id_mahasiswa' => 1,
                'nomor_induk' => '2241760062',
                'username' => '2241760062',
                'nama' => 'Zanuar Aldi Syahputra',
                'id_periode' => 4,
                'id_prodi' => 2,
                'password' => Hash::make('123456789'),
                'jam_alpha' => 6,
                'jam_kompen' => 12,
                'jam_kompen_selesai' => 2,
                'id_level' => 4 
            ],
            [
                'id_mahasiswa' => 2,
                'nomor_induk' => '2241760039',
                'username' => '2241760039',
                'nama' => 'M. Khasbul Hadi Fauzan',
                'id_periode' => 3,
                'id_prodi' => 2,
                'password' => Hash::make('123456789'),
                'jam_alpha' => 20,
                'jam_kompen' => 40,
                'jam_kompen_selesai' => 0,
                'id_level' => 4 
            ],
            [
                'id_mahasiswa' => 3,
                'nomor_induk' => '2241760097',
                'username' => '2241760097',
                'nama' => 'Ahmad Iqbal Firmansyah',
                'id_periode' => 5,
                'id_prodi' => 2,
                'password' => Hash::make('123456789'),
                'jam_alpha' => 50,
                'jam_kompen' => 100,
                'jam_kompen_selesai' => 10,
                'id_level' => 4 
            ],
            [
                'id_mahasiswa' => 4,
                'nomor_induk' => '2241760135',
                'username' => '2241760135',
                'nama' => 'Agung Nugroho',
                'id_periode' => 7,
                'id_prodi' => 2,
                'password' => Hash::make('123456789'),
                'jam_alpha' => 30,
                'jam_kompen' => 60,
                'jam_kompen_selesai' => 0,
                'id_level' => 4 
            ],
            [
                'id_mahasiswa' => 5,
                'nomor_induk' => '2241760100',
                'username' => '2241760100',
                'nama' => 'Arif Prasojo',
                'id_periode' => 1,
                'id_prodi' => 2,
                'password' => Hash::make('123456789'),
                'jam_alpha' => 66,
                'jam_kompen' => 132,
                'jam_kompen_selesai' => 33,
                'id_level' => 4 
            ],
            [
                'id_mahasiswa' => 6,
                'nomor_induk' => '2241760072',
                'username' => '2241760072',
                'nama' => 'Agta Fadjrin Aminullah',
                'id_periode' => 6,
                'id_prodi' => 1,
                'password' => Hash::make('123456789'),
                'jam_alpha' => 0,
                'jam_kompen' => 0,
                'jam_kompen_selesai' => 0,
                'id_level' => 4 
            ],
            [
                'id_mahasiswa' => 7,
                'nomor_induk' => '2241760138',
                'username' => '2241760138',
                'nama' => 'Ervan Dwi Ardian',
                'id_periode' => 5,
                'id_prodi' => 1,
                'password' => Hash::make('123456789'),
                'jam_alpha' => 10,
                'jam_kompen' => 20,
                'jam_kompen_selesai' => 5,
                'id_level' => 4 
            ],
            [
                'id_mahasiswa' => 8,
                'nomor_induk' => '2241760025',
                'username' => '2241760025',
                'nama' => 'Hertin Nurhayati',
                'id_periode' => 4,
                'id_prodi' => 1,
                'password' => Hash::make('123456789'),
                'jam_alpha' => 0,
                'jam_kompen' => 0,
                'jam_kompen_selesai' => 0,
                'id_level' => 4 
            ],
            [
                'id_mahasiswa' => 9,
                'nomor_induk' => '2241760089',
                'username' => '2241760089',
                'nama' => 'Hilmy Zaky Mustakim',
                'id_periode' => 4,
                'id_prodi' => 3,
                'password' => Hash::make('123456789'),
                'jam_alpha' => 30,
                'jam_kompen' => 60,
                'jam_kompen_selesai' => 12,
                'id_level' => 4 
            ],
            [
                'id_mahasiswa' => 10,
                'nomor_induk' => '2241760094',
                'username' => '2241760094',
                'nama' => 'M. Ivan Yoda Bellamy',
                'id_periode' => 5,
                'id_prodi' => 3,
                'password' => Hash::make('123456789'),
                'jam_alpha' => 44,
                'jam_kompen' => 88,
                'jam_kompen_selesai' => 19,
                'id_level' => 4 
            ],
            [
                'id_mahasiswa' => 13,
                'nomor_induk' => '2222222222',
                'username' => '2222222222',
                'nama' => 'Mahasiswa',
                'id_periode' => 5,
                'id_prodi' => 2,
                'password' => Hash::make('123456789'),
                'jam_alpha' => 20,
                'jam_kompen' => 40,
                'jam_kompen_selesai' => 0,
                'id_level' => 4 
            ]
        ];

        DB::table('mahasiswa')->insert($data);
    }
}
