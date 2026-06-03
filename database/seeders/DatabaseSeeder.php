<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     * Data diambil dari SQL dump: sistem_informasi_absensi_sekolah.sql
     *
     * Urutan penting karena ada foreign key:
     * 1. users         (tidak ada FK)
     * 2. gurus         (FK -> users)
     * 3. siswas        (FK -> users)
     * 4. absen_siswas  (FK -> siswas)
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            GuruSeeder::class,
            SiswaSeeder::class,
            AbsenSiswaSeeder::class,
        ]);
    }
}
