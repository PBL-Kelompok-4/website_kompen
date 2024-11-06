<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StatusAcceptacenSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'id_status_acceptance' => 1,
                'kode_status' => 'PND',
                'nama_status' => 'Pending'
            ],
            [
                'id_status_acceptance' => 2,
                'kode_status' => 'ACC',
                'nama_status' => 'Accept'
            ],
            [
                'id_status_acceptance' => 3,
                'kode_status' => 'RJC',
                'nama_status' => 'Reject'
            ]
        ];

        DB::table('status_acceptance')->insert($data);
    }
}
