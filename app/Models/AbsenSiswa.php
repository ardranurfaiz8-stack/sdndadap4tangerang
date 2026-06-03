<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AbsenSiswa extends Model
{
    use HasFactory;

    protected $table = 'absen_siswas';

    protected $fillable = [
        'siswa_id',
        'tanggal',
        'jam_masuk',
        'status',      // hadir | sakit | izin | alpha
        'keterangan',
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }


    public function scopeBulan($query, int $bulan, int $tahun)
    {
        return $query->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun);
    }

    public function getStatusBadgeAttribute(): string
    {
        return match ($this->status) {
            'hadir' => '<span class="badge-hadir">Hadir</span>',
            'sakit' => '<span class="badge-sakit">Sakit</span>',
            'izin'  => '<span class="badge-izin">Izin</span>',
            'alpha' => '<span class="badge-alpha">Alpha</span>',
            default => '<span>-</span>',
        };
    }
}