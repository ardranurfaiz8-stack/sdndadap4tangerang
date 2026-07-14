<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Guru extends Model
{
    use HasFactory;

    protected $table = 'gurus'; // sesuaikan nama tabel

    protected $fillable = [
        'user_id',
        'nama',
        'nip',
        'email',
        'jenis_kelamin',
        'tempat_lahir',
        'tanggal_lahir',
        'alamat',
        'no_telp',
        'mata_pelajaran',
        'foto',
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
    ];


    public function user()
    {
        return $this->belongsTo(User::class);
    }

   public function absenGuru()
    {
    return $this->hasMany(AbsenGuru::class, 'guru_id', 'id');
    
    }   

    public function absensi()
    {
    return $this->hasMany(AbsenGuru::class, 'guru_id', 'id');
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