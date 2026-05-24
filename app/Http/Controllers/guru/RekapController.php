<?php

namespace App\Http\Controllers\guru;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Guru;
use App\Models\Siswa;
use App\Models\AbsenGuru;
use App\Models\AbsenSiswa;
use Carbon\Carbon;

class RekapController extends Controller
{
    /**
     * Halaman rekap absensi bulanan.
     * Menampilkan tabel rekap hadir/izin/sakit/alpha per orang + persentase kehadiran.
     */
    public function index(Request $request)
    {
        $tipe      = $request->get('tipe', 'siswa');
        $bulan     = $request->get('bulan', now()->format('Y-m'));
        $kelas     = $request->get('kelas', '');
        $kelasList = ['1A','1B','2A','2B','3A','3B','4A','4B','5A','5B','6A','6B'];

        try {
            $tglDari   = Carbon::createFromFormat('Y-m', $bulan)->startOfMonth();
            $tglSampai = Carbon::createFromFormat('Y-m', $bulan)->endOfMonth();
        } catch (\Exception $e) {
            $tglDari   = now()->startOfMonth();
            $tglSampai = now()->endOfMonth();
        }

        $rekap        = collect();
        $totalHadir   = 0;
        $totalIzin    = 0;
        $totalSakit   = 0;
        $totalAlpha   = 0;
        $jumlahRecord = 0;

        if ($tipe === 'guru') {
            // ── Rekap Guru ──
            $guruQuery = Guru::query();
            $guruList  = $guruQuery->orderBy('nama')->get();

            $rekap = $guruList->map(function ($g) use ($tglDari, $tglSampai) {
                $absen = AbsenGuru::where('guru_id', $g->id)
                    ->whereBetween('tanggal', [$tglDari, $tglSampai])
                    ->get();

                return (object)[
                    'nama'    => $g->nama,
                    'jabatan' => $g->jabatan,
                    'hadir'   => $absen->where('status', 'Hadir')->count(),
                    'izin'    => $absen->where('status', 'Izin')->count(),
                    'sakit'   => $absen->where('status', 'Sakit')->count(),
                    'alpha'   => $absen->where('status', 'Alpha')->count(),
                ];
            })->filter(fn($r) => ($r->hadir + $r->izin + $r->sakit + $r->alpha) > 0);

        } else {
            // ── Rekap Siswa ──
            $siswaQuery = Siswa::query();
            if ($kelas) $siswaQuery->where('kelas', $kelas);
            $siswaList = $siswaQuery->orderBy('kelas')->orderBy('nama')->get();

            $rekap = $siswaList->map(function ($s) use ($tglDari, $tglSampai) {
                $absen = AbsenSiswa::where('siswa_id', $s->id)
                    ->whereBetween('tanggal', [$tglDari, $tglSampai])
                    ->get();

                return (object)[
                    'nama'  => $s->nama,
                    'kelas' => $s->kelas,
                    'nis'   => $s->nis,
                    'hadir' => $absen->where('status', 'Hadir')->count(),
                    'izin'  => $absen->where('status', 'Izin')->count(),
                    'sakit' => $absen->where('status', 'Sakit')->count(),
                    'alpha' => $absen->where('status', 'Alpha')->count(),
                ];
            })->filter(fn($r) => ($r->hadir + $r->izin + $r->sakit + $r->alpha) > 0);
        }

        // Hitung total keseluruhan
        $totalHadir   = $rekap->sum('hadir');
        $totalIzin    = $rekap->sum('izin');
        $totalSakit   = $rekap->sum('sakit');
        $totalAlpha   = $rekap->sum('alpha');
        $jumlahRecord = $rekap->count();

        // Reset index collection
        $rekap = $rekap->values();

        return view('guru.Rekap', compact(
            'rekap', 'tipe', 'bulan', 'kelas', 'kelasList',
            'totalHadir', 'totalIzin', 'totalSakit', 'totalAlpha',
            'jumlahRecord'
        ));
    }

    /**
     * Cetak rekap (redirect ke index dengan flag print).
     */
    public function cetak(Request $request)
    {
        return $this->index($request);
    }
}