<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class KompenSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'id_kompen' => 1,
                'nomor_kompen' => Str::uuid(),
                'nama' => 'Input Nilai UTS',
                'id_personil' => 2,
                'id_jenis_kompen' => 3,
                'deskripsi' => 'Membantu Menginputkan Nilai UTS',
                'kuota' => 1,
                'jam_kompen' => 4,
                'status' => 'ditutup',
                'is_selesai' => 'no',
                'tanggal_mulai' => now(),
                'tanggal_selesai' => '2024-11-13 13:00:00',
                'status_acceptance' => 3
            ],
            [
                'id_kompen' => 2,
                'nomor_kompen' => Str::uuid(),
                'nama' => 'Uji Coba Aplikasi Sikompen',
                'id_personil' => 1,
                'id_jenis_kompen' => 1,
                'deskripsi' => 'Menguji Aplikasi Sikompen',
                'kuota' => 4,
                'jam_kompen' => 6,
                'status' => 'ditutup',
                'is_selesai' => 'no',
                'tanggal_mulai' => '2024-12-01 16:30:00',
                'tanggal_selesai' => '2024-12-10 08:00:00',
                'status_acceptance' => 1
            ],
            [
                'id_kompen' => 3,
                'nomor_kompen' => Str::uuid(),
                'nama' => 'Bantu Bagi-Bagi Nasi',
                'id_personil' => 3,
                'id_jenis_kompen' => 2,
                'deskripsi' => 'Membantu Program Jurusan Bagi-Bagi Nasi ke Panti Asuhan',
                'kuota' => 2,
                'jam_kompen' => 8,
                'status' => 'ditutup',
                'is_selesai' => 'no',
                'tanggal_mulai' => '2024-12-01 16:30:00',
                'tanggal_selesai' => '2024-12-10 08:00:00',
                'status_acceptance' => 1
            ],
            [
                'id_kompen' => 4,
                'nomor_kompen' => Str::uuid(),
                'nama' => 'Buat Landing Page dan Dasboard',
                'id_personil' => 4,
                'id_jenis_kompen' => 3,
                'deskripsi' => 'Membuat Landing Page Website dan Dashboard menggunakan HTML, CSS, JS',
                'kuota' => 1,
                'jam_kompen' => 10,
                'status' => 'ditutup',
                'is_selesai' => 'no',
                'tanggal_mulai' => '2024-12-01 16:30:00',
                'tanggal_selesai' => '2024-12-10 08:00:00',
                'status_acceptance' => 3
            ],
            [
                'id_kompen' => 5,
                'nomor_kompen' => Str::uuid(),
                'nama' => 'Buat halaman profile aplikasi flutter',
                'id_personil' => 6,
                'id_jenis_kompen' => 3,
                'deskripsi' => 'Membuat Halaman profil aplikasi menggunakan Flutter',
                'kuota' => 1,
                'jam_kompen' => 15,
                'status' => 'dibuka',
                'is_selesai' => 'yes',
                'tanggal_mulai' => '2024-12-01 16:30:00',
                'tanggal_selesai' => '2024-12-10 08:00:00',
                'status_acceptance' => 2
            ],
            [
                'id_kompen' => 6,
                'nomor_kompen' => Str::uuid(),
                'nama' => 'Buat Laporan Keuangan',
                'id_personil' => 7,
                'id_jenis_kompen' => 3,
                'deskripsi' => 'Membuat laporan keuangan menggunakan excel',
                'kuota' => 1,
                'jam_kompen' => 10,
                'status' => 'dibuka',
                'is_selesai' => 'yes',
                'tanggal_mulai' => '2024-12-01 16:30:00',
                'tanggal_selesai' => '2024-12-10 08:00:00',
                'status_acceptance' => 2
            ],
            [
                'id_kompen' => 7,
                'nomor_kompen' => Str::uuid(),
                'nama' => 'Membuat Query agregasi',
                'id_personil' => 5,
                'id_jenis_kompen' => 2,
                'deskripsi' => 'Membuat Query nantinya digunakan untuk analisis kedepannya',
                'kuota' => 1,
                'jam_kompen' => 10,
                'status' => 'dibuka',
                'is_selesai' => 'yes',
                'tanggal_mulai' => '2024-12-01 16:30:00',
                'tanggal_selesai' => '2024-12-10 08:00:00',
                'status_acceptance' => 2
            ],
            [
                'id_kompen' => 8,
                'nomor_kompen' => Str::uuid(),
                'nama' => 'Bersih-bersih',
                'id_personil' => 2,
                'id_jenis_kompen' => 3,
                'deskripsi' => 'Membersihkan toilet LT 7',
                'kuota' => 2,
                'jam_kompen' => 10,
                'status' => 'dibuka',
                'is_selesai' => 'no',
                'tanggal_mulai' => '2024-12-01 16:30:00',
                'tanggal_selesai' => '2024-12-10 08:00:00',
                'status_acceptance' => 2
            ],
            [
                'id_kompen' => 9,
                'nomor_kompen' => Str::uuid(),
                'nama' => 'Bersih PC',
                'id_personil' => 1,
                'id_jenis_kompen' => 3,
                'deskripsi' => 'Membersihkan PC yang ada di ruang kelas',
                'kuota' => 3,
                'jam_kompen' => 5,
                'status' => 'dibuka',
                'is_selesai' => 'no',
                'tanggal_mulai' => '2024-12-01 16:30:00',
                'tanggal_selesai' => '2024-12-10 08:00:00',
                'status_acceptance' => 2
            ]
        ];

        DB::table('kompen')->insert($data);
    }
}