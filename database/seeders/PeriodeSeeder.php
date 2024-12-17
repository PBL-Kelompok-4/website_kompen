<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PeriodeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'periode' => '2018'
            ],
            [
                'periode' => '2019'
            ],
            [
                'periode' => '2020'
            ],
            [
                'periode' => '2021'
            ],
            [
                'periode' => '2022'
            ],
            [
                'periode' => '2023'
            ],
            [
                'periode' => '2024'
            ],
            [
                'periode' => '2025'
            ]
        ];
        DB::table('periode')->insert($data);
    }
}
