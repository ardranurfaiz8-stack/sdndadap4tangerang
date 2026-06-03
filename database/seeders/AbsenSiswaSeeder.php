<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AbsenSiswaSeeder extends Seeder
{
    public function run(): void
    {
        // Data dari SQL dump tabel absen_siswas
        DB::table('absen_siswas')->insert([
            [
                'id'         => 2,
                'siswa_id'   => 2,
                'tanggal'    => '2026-05-13',
                'jam_masuk'  => null,
                'status'     => 'izin',
                'keterangan' => null,
                'created_at' => '2026-05-13 00:28:48',
                'updated_at' => '2026-05-13 00:41:50',
            ],
            [
                'id'         => 3,
                'siswa_id'   => 3,
                'tanggal'    => '2026-05-13',
                'jam_masuk'  => null,
                'status'     => 'sakit',
                'keterangan' => null,
                'created_at' => '2026-05-13 00:28:48',
                'updated_at' => '2026-05-13 00:51:16',
            ],
            [
                'id'         => 4,
                'siswa_id'   => 3,
                'tanggal'    => '2026-05-17',
                'jam_masuk'  => null,
                'status'     => 'hadir',
                'keterangan' => null,
                'created_at' => '2026-05-17 03:14:18',
                'updated_at' => '2026-05-17 03:14:18',
            ],
        ]);

        DB::statement('ALTER TABLE absen_siswas AUTO_INCREMENT = 5');
    }
}
