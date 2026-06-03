<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Kolom role & username sudah dimasukkan ke create_users_table.
// File ini dipertahankan agar urutan migration tidak berubah.
return new class extends Migration
{
    public function up(): void
    {
        // no-op: role sudah ada di create_users_table
    }

    public function down(): void
    {
        // no-op
    }
};
