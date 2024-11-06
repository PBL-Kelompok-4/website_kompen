<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KompetensiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'id_kompetensi' => 1,
                'nama_kompetensi' => 'SQL Dasar',
                'deskripsi_kompetensi' => 'Menguasai Query SQL Dasar seperti Select, Update, Delete'
            ],
            [
                'id_kompetensi' => 2,
                'nama_kompetensi' => 'JAVA Dasar',
                'deskripsi_kompetensi' => 'Menguasai Pemrograman JAVA dasar'
            ],
            [
                'id_kompetensi' => 3,
                'nama_kompetensi' => 'JAVA Lanjut',
                'deskripsi_kompetensi' => 'Mampu menerapkan konsep OOP dalam JAVA dan penerapan Algoritma lainnya'
            ],
            [
                'id_kompetensi' => 4,
                'nama_kompetensi' => 'WEB Dasar',
                'deskripsi_kompetensi' => 'Mampu Membuat halaman sederhana menggunakan HTML, CSS, Dan JS'
            ],
            [
                'id_kompetensi' => 5,
                'nama_kompetensi' => 'WEB Lanjut Laravel',
                'deskripsi_kompetensi' => 'Mampu membuat projek sederhana menggunakan framework Laravel'
            ],
            [
                'id_kompetensi' => 6,
                'nama_kompetensi' => 'Web Lanjut React',
                'deskripsi_kompetensi' => 'Mampu membuat projek sederhana menggunakan framework React'
            ],
            [
                'id_kompetensi' => 7,
                'nama_kompetensi' => 'Web Tingat Dewa',
                'deskripsi_kompetensi' => 'Mampu membuat projek menggunakan Web3'
            ],
            [
                'id_kompetensi' => 8,
                'nama_kompetensi' => 'Excel Dasar',
                'deskripsi_kompetensi' => 'Menguasai rumus excel sederhana seperti agregasi dan pivot table'
            ],
            [
                'id_kompetensi' => 9,
                'nama_kompetensi' => 'Mobile Dasar Flutter',
                'deskripsi_kompetensi' => 'Mampu membuat page menggunakan Flutter'
            ],
            [
                'id_kompetensi' => 10,
                'nama_kompetensi' => 'Mobile Dasar Kotlin',
                'deskripsi_kompetensi' => 'Mampu membuat page menggunakan Kotlin'
            ],
        ];

        DB::table('kompetensi')->insert($data);
    }
}
