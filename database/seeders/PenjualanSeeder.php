<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PenjualanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'penjualan_id' => 1,
                'user_id' => 1,
                'pembeli' => 'Aldi',
                'penjualan_kode' => 'PJL-001',
                'penjualan_tanggal' => '2024-09-14 12:22:00'
            ],
            [
                'penjualan_id' => 2,
                'user_id' => 1,
                'pembeli' => 'Iqbal',
                'penjualan_kode' => 'PJL-002',
                'penjualan_tanggal' => '2024-09-13 22:22:00'
            ],
            [
                'penjualan_id' => 3,
                'user_id' => 1,
                'pembeli' => 'Arif',
                'penjualan_kode' => 'PJL-003',
                'penjualan_tanggal' => '2024-09-12 14:22:00'
            ],
            [
                'penjualan_id' => 4,
                'user_id' => 2,
                'pembeli' => 'Agung',
                'penjualan_kode' => 'PJL-004',
                'penjualan_tanggal' => '2024-09-11 13:22:00'
            ],
            [
                'penjualan_id' => 5,
                'user_id' => 2,
                'pembeli' => 'Hilmy',
                'penjualan_kode' => 'PJL-005',
                'penjualan_tanggal' => '2024-09-14 12:19:00'
            ],
            [
                'penjualan_id' => 6,
                'user_id' => 2,
                'pembeli' => 'Dane',
                'penjualan_kode' => 'PJL-006',
                'penjualan_tanggal' => '2024-09-13 21:43:00'
            ],
            [
                'penjualan_id' => 7,
                'user_id' => 3,
                'pembeli' => 'Nala',
                'penjualan_kode' => 'PJL-007',
                'penjualan_tanggal' => '2024-09-12 17:33:00'
            ],
            [
                'penjualan_id' => 8,
                'user_id' => 3,
                'pembeli' => 'Atif',
                'penjualan_kode' => 'PJL-008',
                'penjualan_tanggal' => '2024-09-11 17:57:00'
            ],
            [
                'penjualan_id' => 9,
                'user_id' => 3,
                'pembeli' => 'Nadhif',
                'penjualan_kode' => 'PJL-009',
                'penjualan_tanggal' => '2024-09-14 08:55:00'
            ],
            [
                'penjualan_id' => 10,
                'user_id' => 3,
                'pembeli' => 'Nasywa',
                'penjualan_kode' => 'PJL-010',
                'penjualan_tanggal' => '2024-09-13 11:18:00'
            ]
        ];
        DB::table('t_penjualan')->insert($data);
    }
}
