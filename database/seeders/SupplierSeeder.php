<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SupplierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'supplier_id' => 1,
                'supplier_kode' => 'SUP-001',
                'supplier_nama' => 'PT. IndoMarco',
                'supplier_alamat' => 'Pasuruan'
            ],
            [
                'supplier_id' => 2,
                'supplier_kode' => 'SUP-002',
                'supplier_nama' => 'PT. Maju Bersama',
                'supplier_alamat' => 'Malang'
            ],
            [
                'supplier_id' => 3,
                'supplier_kode' => 'SUP-003',
                'supplier_nama' => 'PT. Susah Tidur',
                'supplier_alamat' => 'Surabaya'
            ]
        ];
        DB::table('m_supplier')->insert($data);
    }
}
