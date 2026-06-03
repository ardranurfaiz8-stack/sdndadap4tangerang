<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Data dari SQL dump — password sudah di-hash, dipakai langsung
        // Password asli: (unknown dari dump), tapi kita set ulang ke 'password123'
        DB::table('users')->insert([
            [
                'id'         => 3,
                'name'       => 'Budi Santoso',
                'username'   => null,
                'email'      => 'budi@gmail.com',
                'password'   => Hash::make('password123'),
                'role'       => 'siswa',
                'created_at' => '2026-05-06 21:55:59',
                'updated_at' => '2026-05-06 21:55:59',
            ],
            [
                'id'         => 8,
                'name'       => 'guru',
                'username'   => null,
                'email'      => 'guru@gmail.com',
                'password'   => Hash::make('password123'),
                'role'       => 'guru',
                'created_at' => null,
                'updated_at' => null,
            ],
            [
                'id'         => 9,
                'name'       => 'kepala sekolah',
                'username'   => null,
                'email'      => 'kepalasekolah@gmail.com',
                'password'   => Hash::make('password123'),
                'role'       => 'kepala_sekolah',
                'created_at' => '2026-05-07 08:46:07',
                'updated_at' => '2026-05-07 08:46:07',
            ],
            [
                'id'         => 10,
                'name'       => 'admin',
                'username'   => null,
                'email'      => 'admin@gmail.com',
                'password'   => Hash::make('password123'),
                'role'       => 'admin',
                'created_at' => '2026-05-08 02:44:52',
                'updated_at' => '2026-05-08 02:44:52',
            ],
            [
                'id'         => 11,
                'name'       => 'Guru_Hendra',
                'username'   => null,
                'email'      => 'Guru_Hendra@gmail.com',
                'password'   => Hash::make('password123'),
                'role'       => 'guru',
                'created_at' => '2026-05-19 03:20:46',
                'updated_at' => '2026-05-19 03:20:46',
            ],
            [
                'id'         => 12,
                'name'       => 'Guru_Sari',
                'username'   => null,
                'email'      => 'Guru_sari@gmail.com',
                'password'   => Hash::make('password123'),
                'role'       => 'guru',
                'created_at' => '2026-05-19 03:40:41',
                'updated_at' => '2026-05-19 03:40:41',
            ],
        ]);

        // Set auto increment setelah insert dengan ID eksplisit
        DB::statement('ALTER TABLE users AUTO_INCREMENT = 14');
    }
}
