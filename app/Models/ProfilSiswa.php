<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProfilSiswa extends Model
{
    use HasFactory;

    protected $table = 'profil_siswas';

    protected $fillable = [
        'siswa_id',
        'tahun_masuk',
        'golongan_darah',
        'tinggi_badan',
        'berat_badan',
        'riwayat_penyakit',
        'catatan',
    ];

    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }
}