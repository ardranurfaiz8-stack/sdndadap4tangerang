<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Siswa extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'nama',
        'nis',
        'email',
        'kelas',
        'jenis_kelamin',
        'tempat_lahir',
        'tanggal_lahir',
        'alamat',
        'no_telp',
        'nama_orang_tua',
        'foto',
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function absenSiswa()
    {
        return $this->hasMany(AbsenSiswa::class);
    }

    public function profil()
    {
        return $this->hasOne(ProfilSiswa::class);
    }

    public function rekapAbsen()
    {
        return $this->hasMany(RekapAbsenSiswa::class);
    }
}