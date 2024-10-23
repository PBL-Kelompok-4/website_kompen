<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BarangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'barang_id' => 1,
                'kategori_id' => 1,
                'barang_kode' => 'BRG_001',
                'barang_nama' => 'Setrika',
                'harga_beli' => 200000,
                'harga_jual' => 220000
            ],
            [
                'barang_id' => 2,
                'kategori_id' => 1,
                'barang_kode' => 'BRG_002',
                'barang_nama' => 'Magic com',
                'harga_beli' => 600000,
                'harga_jual' => 635000
            ],
            [
                'barang_id' => 3,
                'kategori_id' => 1,
                'barang_kode' => 'BRG_003',
                'barang_nama' => 'Kipas Angin',
                'harga_beli' => 300000,
                'harga_jual' => 345000
            ],
            [
                'barang_id' => 4,
                'kategori_id' => 2,
                'barang_kode' => 'BRG_004',
                'barang_nama' => 'Jam Tangan Pria Navy',
                'harga_beli' => 200000,
                'harga_jual' => 229000
            ],
            [
                'barang_id' => 5,
                'kategori_id' => 2,
                'barang_kode' => 'BRG_005',
                'barang_nama' => 'Sepatu Compass Retrograde',
                'harga_beli' => 500000,
                'harga_jual' => 540000
            ],
            [
                'barang_id' => 6,
                'kategori_id' => 2,
                'barang_kode' => 'BRG_006',
                'barang_nama' => 'Torch Tas Pinggang Selempang',
                'harga_beli' => 140000,
                'harga_jual' => 160000
            ],
            [
                'barang_id' => 7,
                'kategori_id' => 3,
                'barang_kode' => 'BRG_007',
                'barang_nama' => 'Tas Selempang Wanita',
                'harga_beli' => 429000,
                'harga_jual' => 475000
            ],
            [
                'barang_id' => 8,
                'kategori_id' => 3,
                'barang_kode' => 'BRG_008',
                'barang_nama' => 'WINOD Enzi Flat Shoes',
                'harga_beli' => 80000,
                'harga_jual' => 100000
            ],
            [
                'barang_id' => 9,
                'kategori_id' => 3,
                'barang_kode' => 'BRG_009',
                'barang_nama' => 'Atasan Kemeja Wanita Peach Home',
                'harga_beli' => 340000,
                'harga_jual' => 370000
            ],
            [
                'barang_id' => 10,
                'kategori_id' => 4,
                'barang_kode' => 'BRG_010',
                'barang_nama' => 'Baseus Fast Charger Type C to C',
                'harga_beli' => 60000,
                'harga_jual' => 70000
            ],
            [
                'barang_id' => 11,
                'kategori_id' => 4,
                'barang_kode' => 'BRG_011',
                'barang_nama' => 'vivo Y27 (6/128)',
                'harga_beli' => 1500000,
                'harga_jual' => 1600000
            ],
            [
                'barang_id' => 12,
                'kategori_id' => 4,
                'barang_kode' => 'BRG_012',
                'barang_nama' => 'Car Holder Mobil Dudukan HP',
                'harga_beli' => 60000,
                'harga_jual' => 75000
            ],
            [
                'barang_id' => 13,
                'kategori_id' => 5,
                'barang_kode' => 'BRG_013',
                'barang_nama' => 'Logitech M220 Mouse Wireless Silent Click',
                'harga_beli' => 170000,
                'harga_jual' => 186000
            ],
            [
                'barang_id' => 14,
                'kategori_id' => 5,
                'barang_kode' => 'BRG_014',
                'barang_nama' => 'Ajazz AK820 GTS PRO 75%',
                'harga_beli' => 400000,
                'harga_jual' => 430000
            ],
            [
                'barang_id' => 15,
                'kategori_id' => 5,
                'barang_kode' => 'BRG_015',
                'barang_nama' => 'ZOTAC GAMING GeForce RTX 4060 8GB',
                'harga_beli' => 4600000,
                'harga_jual' => 4800000
            ]
        ];
        DB::table('m_barang')->insert($data);
    }
}
