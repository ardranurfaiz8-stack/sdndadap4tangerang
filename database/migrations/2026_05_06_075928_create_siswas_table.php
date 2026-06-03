<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('siswas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('nama');
            $table->string('nis')->unique()->nullable();
            $table->string('email')->unique()->nullable();
            $table->string('kelas')->nullable();
            $table->enum('jenis_kelamin', ['L', 'P', 'Laki-laki', 'Perempuan'])->nullable();
            $table->string('tempat_lahir')->nullable();
            $table->date('tanggal_lahir')->nullable();
            $table->text('alamat')->nullable();
            $table->string('no_telp')->nullable();
            $table->string('nama_orang_tua')->nullable();
            $table->string('foto')->nullable();
            $table->timestamps();
            // Kolom tambahan (sebelumnya di migration add_extra_fields)
            $table->string('tahun_masuk')->nullable();
            $table->string('nama_ayah')->nullable();
            $table->string('nama_ibu')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('siswas');
    }
};
