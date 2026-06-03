<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Kolom tambahan siswas sudah dimasukkan ke create_siswas_table.
// File ini dipertahankan agar urutan migration tidak berubah.
return new class extends Migration
{
    public function up(): void
    {
        // no-op: tahun_masuk, nama_ayah, nama_ibu sudah ada di create_siswas_table
    }

    public function down(): void
    {
        // no-op
    }
};
