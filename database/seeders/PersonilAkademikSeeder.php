<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class PersonilAkademikSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'id_personil' => 1,
                'nomor_induk' => '000000000000000001',
                'username' => 'Tirta',
                'nama' => 'Tirta Mandira Hudhi',
                'password' => Hash::make('123456789'),
                'nomor_telp' => '081234567890121',
                'id_level' => 2
            ],
            [
                'id_personil' => 2,
                'nomor_induk' => '000000000000000002',
                'username' => 'Dika',
                'nama' => 'Raditya Dika',
                'password' => Hash::make('123456789'),
                'nomor_telp' => '081234567890122',
                'id_level' => 2
            ],
            [
                'id_personil' => 3,
                'nomor_induk' => '000000000000000003',
                'username' => 'Richard',
                'nama' => 'Richard Lee',
                'password' => Hash::make('123456789'),
                'nomor_telp' => '081234567890123',
                'id_level' => 1
            ],
            [
                'id_personil' => 4,
                'nomor_induk' => '000000000000000004',
                'username' => 'David',
                'nama' => 'David Brendi',
                'password' => Hash::make('123456789'),
                'nomor_telp' => '081234567890124',
                'id_level' => 2
            ],
            [
                'id_personil' => 5,
                'nomor_induk' => '000000000000000005',
                'username' => 'Erpan1140',
                'nama' => 'Erpan From Mars',
                'password' => Hash::make('123456789'),
                'nomor_telp' => '081234567890125',
                'id_level' => 1
            ],
            [
                'id_personil' => 6,
                'nomor_induk' => '000000000000000006',
                'username' => 'Garit',
                'nama' => 'Garit Dewana',
                'password' => Hash::make('123456789'),
                'nomor_telp' => '081234567890126',
                'id_level' => 3
            ],
            [
                'id_personil' => 7,
                'nomor_induk' => '000000000000000007',
                'username' => 'Ewing 4K',
                'nama' => 'Hujwiriawan Ewing',
                'password' => Hash::make('123456789'),
                'nomor_telp' => '081234567890127',
                'id_level' => 3
            ],
            [
                'id_personil' => 8,
                'nomor_induk' => '000000000000000008',
                'username' => 'Ferry',
                'nama' => 'Ferry Irwandi',
                'password' => Hash::make('123456789'),
                'nomor_telp' => '081234567890128',
                'id_level' => 1
            ],
            [
                'id_personil' => 9,
                'nomor_induk' => '000000000000000009',
                'username' => 'Dosen',
                'nama' => 'Dosen',
                'password' => Hash::make('123456789'),
                'nomor_telp' => '081234567890128',
                'id_level' => 2
            ],
            [
                'id_personil' => 10,
                'nomor_induk' => '000000000000000010',
                'username' => 'Admin',
                'nama' => 'Admin',
                'password' => Hash::make('123456789'),
                'nomor_telp' => '081234567890128',
                'id_level' => 1
            ],
            [
                'id_personil' => 11,
                'nomor_induk' => '000000000000000011',
                'username' => 'Tendik',
                'nama' => 'Tendik',
                'password' => Hash::make('123456789'),
                'nomor_telp' => '081234567890128',
                'id_level' => 3
            ]
        ];

        DB::table('personil_akademik')->insert($data);
    }
}
