<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable; // ← yang benar ini
use App\Models\Absensi;

class Guru extends Authenticatable
{
    use HasFactory;

    protected $table = 'gurus'; // sesuaikan nama tabel

   protected $fillable = [
    'nama',
    'nip',
    'jabatan',
    'jenis_kelamin',
    'no_telp',       // ✅ bukan no_hp
    'email',
    'password',
    'alamat',
    'mata_pelajaran',
    'foto',
];

    protected $hidden = [
        'password',
    ];


    public function user()
    {
        return $this->belongsTo(User::class);
    }

   public function absenGuru()
    {
    return $this->hasMany(Absensi::class, 'guru_id', 'id');
    
    }   

    public function absensi()
    {
    return $this->hasMany(Absensi::class, 'guru_id', 'id');
    }
    public function profil()
    {
        return $this->hasOne(ProfilGuru::class);
    }

    public function rekapAbsen()
    {
        return $this->hasMany(RekapAbsenGuru::class);
    }

    // Accessor nama lengkap dengan gelar (jika ada)
    public function getNamaLengkapAttribute(): string
    {
        return $this->nama;
    }
}