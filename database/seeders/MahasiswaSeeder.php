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
                'username' => 'Aldi',
                'nama' => 'Zanuar Aldi Syahputra',
                'semester' => 5,
                'id_prodi' => 2,
                'password' => Hash::make('123456789'),
                'jam_alpha' => 6,
                'jam_kompen' => 4,
                'jam_kompen_selesai' => 2,
                'id_level' => 4
            ],
            [
                'id_mahasiswa' => 2,
                'nomor_induk' => '2241760060',
                'username' => 'Bulbul',
                'nama' => 'M. Khasbul Hadi Fauzan',
                'semester' => 5,
                'id_prodi' => 2,
                'password' => Hash::make('123456789'),
                'jam_alpha' => 20,
                'jam_kompen' => 15,
                'jam_kompen_selesai' => 0,
                'id_level' => 4
            ],
            [
                'id_mahasiswa' => 3,
                'nomor_induk' => '2241760070',
                'username' => 'Iqbal',
                'nama' => 'Ahmad Iqbal Firmansyah',
                'semester' => 5,
                'id_prodi' => 2,
                'password' => Hash::make('123456789'),
                'jam_alpha' => 50,
                'jam_kompen' => 50,
                'jam_kompen_selesai' => 10,
                'id_level' => 4 
            ],
            [
                'id_mahasiswa' => 4,
                'nomor_induk' => '2241760042',
                'username' => 'Agung',
                'nama' => 'Agung Nugroho',
                'semester' => 5,
                'id_prodi' => 2,
                'password' => Hash::make('123456789'),
                'jam_alpha' => 30,
                'jam_kompen' => 23,
                'jam_kompen_selesai' => 0,
                'id_level' => 4 
            ],
            [
                'id_mahasiswa' => 5,
                'nomor_induk' => '2241760023',
                'username' => 'Arif',
                'nama' => 'Arif Prasojo',
                'semester' => 5,
                'id_prodi' => 2,
                'password' => Hash::make('123456789'),
                'jam_alpha' => 66,
                'jam_kompen' => 33,
                'jam_kompen_selesai' => 33,
                'id_level' => 4 
            ],
            [
                'id_mahasiswa' => 6,
                'nomor_induk' => '2241760001',
                'username' => 'Agta',
                'nama' => 'Agta Fadjrin Aminullah',
                'semester' => 5,
                'id_prodi' => 1,
                'password' => Hash::make('123456789'),
                'jam_alpha' => 0,
                'jam_kompen' => 0,
                'jam_kompen_selesai' => 0,
                'id_level' => 4 
            ],
            [
                'id_mahasiswa' => 7,
                'nomor_induk' => '224176077',
                'username' => 'Ervan',
                'nama' => 'Ervan Dwi Ardian',
                'semester' => 5,
                'id_prodi' => 1,
                'password' => Hash::make('123456789'),
                'jam_alpha' => 10,
                'jam_kompen' => 9,
                'jam_kompen_selesai' => 5,
                'id_level' => 4 
            ],
            [
                'id_mahasiswa' => 8,
                'nomor_induk' => '2241760069',
                'username' => 'Hertin',
                'nama' => 'Hertin Nurhayati',
                'semester' => 5,
                'id_prodi' => 1,
                'password' => Hash::make('123456789'),
                'jam_alpha' => 0,
                'jam_kompen' => 0,
                'jam_kompen_selesai' => 0,
                'id_level' => 4
            ],
            [
                'id_mahasiswa' => 9,
                'nomor_induk' => '2241760087',
                'username' => 'Hilmy',
                'nama' => 'Hilmy Zaky Mustakim',
                'semester' => 5,
                'id_prodi' => 3,
                'password' => Hash::make('123456789'),
                'jam_alpha' => 30,
                'jam_kompen' => 24,
                'jam_kompen_selesai' => 12,
                'id_level' => 4
            ],
            [
                'id_mahasiswa' => 10,
                'nomor_induk' => '2241760080',
                'username' => 'Ivan',
                'nama' => 'M. Ivan Yoda Bellamy',
                'semester' => 5,
                'id_prodi' => 3,
                'password' => Hash::make('123456789'),
                'jam_alpha' => 44,
                'jam_kompen' => 32,
                'jam_kompen_selesai' => 19,
                'id_level' => 4
            ]
        ];

        DB::table('mahasiswa')->insert($data);
    }
}
