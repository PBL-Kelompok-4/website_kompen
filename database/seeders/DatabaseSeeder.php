<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        /* Sebagai Parent */
        $this->call(ProdiSeeder::class);
        $this->call(LevelSeeder::class);
        $this->call(KompetensiSeeder::class);
        $this->call(JenisKompenSeeder::class);

        /* CHILD 1 : Foreign key dari Parent */
        $this->call(MahasiswaSeeder::class);
        $this->call(PersonilAkademikSeeder::class);
        $this->call(KompenSeeder::class);
        $this->call(ListKompetensiMahasiswaSeeder::class);
    }
}
