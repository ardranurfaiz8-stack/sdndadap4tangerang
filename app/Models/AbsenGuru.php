<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AbsenGuru extends Model
{
    use HasFactory;

    protected $table = 'absen_gurus';

    protected $fillable = [
        'guru_id',
        'tanggal',
        'jam_masuk',
        'jam_keluar',
        'status',      // hadir | sakit | izin | alpha
        'keterangan',
        'foto_bukti',
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    public function guru()
    {
        return $this->belongsTo(Guru::class);
    }

    // Scope filter per bulan
    public function scopeBulan($query, int $bulan, int $tahun)
    {
        return $query->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun);
    }

    // Label status berwarna untuk badge
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