<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RekapAbsenSiswa extends Model
{
    use HasFactory;

    protected $table = 'rekap_absen_siswas';

    protected $fillable = [
        'siswa_id',
        'bulan',
        'tahun',
        'total_hadir',
        'total_sakit',
        'total_izin',
        'total_alpha',
        'total_hari_belajar',
        'persentase_kehadiran',
    ];

    protected $casts = [
        'persentase_kehadiran' => 'decimal:2',
    ];

    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }

    /**
     * Generate / refresh rekap untuk satu siswa satu bulan.
     */
    public static function generate(int $siswaId, int $bulan, int $tahun): self
    {
        $absen = AbsenSiswa::where('siswa_id', $siswaId)
            ->whereMonth('tanggal', $bulan)
            ->whereYear('tanggal', $tahun)
            ->get();

        $hadir       = $absen->where('status', 'hadir')->count();
        $hariBelajar = max($absen->count(), 1);

        return static::updateOrCreate(
            ['siswa_id' => $siswaId, 'bulan' => $bulan, 'tahun' => $tahun],
            [
                'total_hadir'          => $hadir,
                'total_sakit'          => $absen->where('status', 'sakit')->count(),
                'total_izin'           => $absen->where('status', 'izin')->count(),
                'total_alpha'          => $absen->where('status', 'alpha')->count(),
                'total_hari_belajar'   => $hariBelajar,
                'persentase_kehadiran' => round(($hadir / $hariBelajar) * 100, 2),
            ]
        );
    }
}