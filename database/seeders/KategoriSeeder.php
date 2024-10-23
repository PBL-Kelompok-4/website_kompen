<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KategoriSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'kategori_id' => 1,
                'kategori_kode' => 'KTG-001',
                'kategori_nama' => 'Elektronik'
            ],
            [
                'kategori_id' => 2,
                'kategori_kode' => 'KTG-002',
                'kategori_nama' => 'Fashion Pria'
            ],
            [
                'kategori_id' => 3,
                'kategori_kode' => 'KTG-003',
                'kategori_nama' => 'Fashion Wanita'
            ],
            [
                'kategori_id' => 4,
                'kategori_kode' => 'KTG-004',
                'kategori_nama' => 'Handphone & Tablet'
            ],
            [
                'kategori_id' => 5,
                'kategori_kode' => 'KTG-005',
                'kategori_nama' => 'Komputer & Laptop'
            ]
        ];
        DB::table('m_kategori')->insert($data);
    }
}
