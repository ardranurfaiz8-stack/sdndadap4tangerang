<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SiswaSeeder extends Seeder
{
    public function run(): void
    {
        // Data dari SQL dump tabel siswas
        DB::table('siswas')->insert([
            [
                'id'             => 2,
                'user_id'        => null,
                'nama'           => 'Siti Rahayu',
                'nis'            => '2024002',
                'email'          => null,
                'kelas'          => '2A',
                'jenis_kelamin'  => 'Perempuan',
                'tempat_lahir'   => 'Bogor',
                'tanggal_lahir'  => '2021-05-20',
                'alamat'         => 'Jl. Cempaka No. 12, Bogor',
                'no_telp'        => '0822-8765-4321',
                'nama_orang_tua' => 'Sari',
                'foto'           => null,
                'tahun_masuk'    => null,
                'nama_ayah'      => null,
                'nama_ibu'       => null,
                'created_at'     => '2026-05-13 00:26:15',
                'updated_at'     => '2026-05-13 00:26:15',
            ],
            [
                'id'             => 3,
                'user_id'        => null,
                'nama'           => 'Ardra',
                'nis'            => '2024004',
                'email'          => 'ardranurfaiz43@gmail.com',
                'kelas'          => '5A',
                'jenis_kelamin'  => 'L',
                'tempat_lahir'   => 'Jakarta',
                'tanggal_lahir'  => '2026-05-17',
                'alamat'         => 'Jl. Melati No. 8, Jakarta',
                'no_telp'        => '0823-7654-3211',
                'nama_orang_tua' => 'Fauzan',
                'foto'           => null,
                'tahun_masuk'    => null,
                'nama_ayah'      => null,
                'nama_ibu'       => null,
                'created_at'     => '2026-05-13 00:27:43',
                'updated_at'     => '2026-05-17 03:10:47',
            ],
            [
                'id'             => 4,
                'user_id'        => null,
                'nama'           => 'Nopal',
                'nis'            => '2024005',
                'email'          => null,
                'kelas'          => '6A',
                'jenis_kelamin'  => 'Laki-laki',
                'tempat_lahir'   => 'Tangerang',
                'tanggal_lahir'  => '2026-05-17',
                'alamat'         => 'Jl. Tanah Tinggi 13',
                'no_telp'        => '0822-8765-4321',
                'nama_orang_tua' => 'Sari',
                'foto'           => null,
                'tahun_masuk'    => null,
                'nama_ayah'      => null,
                'nama_ibu'       => null,
                'created_at'     => '2026-05-17 03:16:04',
                'updated_at'     => '2026-05-17 03:16:04',
            ],
        ]);

        DB::statement('ALTER TABLE siswas AUTO_INCREMENT = 6');
    }
}
