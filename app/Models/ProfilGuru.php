<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProfilGuru extends Model
{
    use HasFactory;

    protected $table = 'profil_gurus';

    protected $fillable = [
        'guru_id',
        'pendidikan_terakhir',
        'tahun_masuk',
        'status_kepegawaian', // PNS | Honorer | PPPK
        'catatan',
    ];

    public function guru()
    {
        return $this->belongsTo(Guru::class);
    }
}