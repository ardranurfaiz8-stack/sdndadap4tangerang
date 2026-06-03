<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GuruSeeder extends Seeder
{
    public function run(): void
    {
        // Data dari SQL dump tabel gurus
        DB::table('gurus')->insert([
            [
                'id'             => 8,
                'user_id'        => null,
                'nama'           => 'Hendra Wijaya',
                'nip'            => '198502112010012',
                'email'          => 'hendra@sekolah.ac.id',
                'jenis_kelamin'  => 'Laki-laki',
                'tempat_lahir'   => null,
                'tanggal_lahir'  => null,
                'alamat'         => 'JL.MERDEKA SELATAN12',
                'no_telp'        => '0823-7654-3211',
                'mata_pelajaran' => null,
                'foto'           => null,
                'created_at'     => '2026-05-20 10:20:42',
                'updated_at'     => '2026-05-20 10:41:13',
            ],
        ]);

        DB::statement('ALTER TABLE gurus AUTO_INCREMENT = 9');
    }
}
