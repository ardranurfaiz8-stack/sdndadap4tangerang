<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RekapAbsenGuru extends Model
{
    use HasFactory;

    protected $table = 'rekap_absen_gurus';

    protected $fillable = [
        'guru_id',
        'bulan',
        'tahun',
        'total_hadir',
        'total_sakit',
        'total_izin',
        'total_alpha',
        'total_hari_kerja',
        'persentase_kehadiran',
    ];

    protected $casts = [
        'persentase_kehadiran' => 'decimal:2',
    ];

    public function guru()
    {
        return $this->belongsTo(Guru::class);
    }

    /**
     * Generate / refresh rekap untuk satu guru satu bulan.
     */
    public static function generate(int $guruId, int $bulan, int $tahun): self
    {
        $absen = AbsenGuru::where('guru_id', $guruId)
            ->whereMonth('tanggal', $bulan)
            ->whereYear('tanggal', $tahun)
            ->get();

        $hadir      = $absen->where('status', 'hadir')->count();
        $hariKerja  = max($absen->count(), 1);

        return static::updateOrCreate(
            ['guru_id' => $guruId, 'bulan' => $bulan, 'tahun' => $tahun],
            [
                'total_hadir'          => $hadir,
                'total_sakit'          => $absen->where('status', 'sakit')->count(),
                'total_izin'           => $absen->where('status', 'izin')->count(),
                'total_alpha'          => $absen->where('status', 'alpha')->count(),
                'total_hari_kerja'     => $hariKerja,
                'persentase_kehadiran' => round(($hadir / $hariKerja) * 100, 2),
            ]
        );
    }
}