<?php

namespace App\Http\Controllers\kepala_sekolah;

use App\Http\Controllers\Controller;
use App\Models\Guru;
use App\Models\AbsenGuru;
use App\Models\AbsenSiswa;
use App\Models\Siswa;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $kepalaSekolah = (object)[
            'nama' => Auth::user()->name,
            'nip'  => Auth::user()->username ?? '-',
        ];

        $today    = Carbon::today()->toDateString();
        $bulan    = Carbon::now()->month;
        $tahun    = Carbon::now()->year;

        // ===== STATS =====
        $totalGuru  = Guru::count();
        $totalSiswa = Siswa::count();

        $guruHadir = AbsenGuru::whereDate('tanggal', $today)->where('status', 'hadir')->count();
        $guruAlpha = AbsenGuru::whereDate('tanggal', $today)->where('status', 'alpha')->count();

        $siswaHadir = AbsenSiswa::whereDate('tanggal', $today)->where('status', 'hadir')->count();
        $siswaAlpha = AbsenSiswa::whereDate('tanggal', $today)->where('status', 'alpha')->count();

        $stats = [
            'total_guru'  => $totalGuru,
            'total_siswa' => $totalSiswa,
            'guru_hadir'  => $guruHadir,
            'guru_alpha'  => $guruAlpha,
            'siswa_hadir' => $siswaHadir,
            'siswa_alpha' => $siswaAlpha,
        ];

        // ===== REKAP BULAN INI =====
        $absenGuruBulan = AbsenGuru::whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->get();
        $rekapGuru = [
            'hadir' => $absenGuruBulan->where('status', 'hadir')->count(),
            'sakit' => $absenGuruBulan->where('status', 'sakit')->count(),
            'izin'  => $absenGuruBulan->where('status', 'izin')->count(),
            'alpha' => $absenGuruBulan->where('status', 'alpha')->count(),
        ];

        $absenSiswaBulan = AbsenSiswa::whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->get();
        $rekapSiswa = [
            'hadir' => $absenSiswaBulan->where('status', 'hadir')->count(),
            'sakit' => $absenSiswaBulan->where('status', 'sakit')->count(),
            'izin'  => $absenSiswaBulan->where('status', 'izin')->count(),
            'alpha' => $absenSiswaBulan->where('status', 'alpha')->count(),
        ];

        // ===== GURU ALPHA TERBANYAK =====
        $guruTerendah = Guru::withCount(['absenGuru as alpha_count' => function ($q) use ($bulan, $tahun) {
            $q->whereMonth('tanggal', $bulan)
              ->whereYear('tanggal', $tahun)
              ->where('status', 'alpha');
        }])
        ->orderByDesc('alpha_count')
        ->limit(5)
        ->get();

        // ===== CHART TREN 6 BULAN =====
        $chartLabels    = [];
        $chartDataGuru  = [];
        $chartDataSiswa = [];

        for ($i = 5; $i >= 0; $i--) {
            $bulanChart = Carbon::now()->subMonths($i);
            $chartLabels[] = $bulanChart->translatedFormat('M Y');

            $chartDataGuru[] = AbsenGuru::whereMonth('tanggal', $bulanChart->month)
                ->whereYear('tanggal', $bulanChart->year)
                ->where('status', 'hadir')
                ->count();

            $chartDataSiswa[] = AbsenSiswa::whereMonth('tanggal', $bulanChart->month)
                ->whereYear('tanggal', $bulanChart->year)
                ->where('status', 'hadir')
                ->count();
        }

        return view('kepala_sekolah.Dashboard', compact(
            'kepalaSekolah',
            'stats',
            'rekapGuru',
            'rekapSiswa',
            'guruTerendah',
            'chartLabels',
            'chartDataGuru',
            'chartDataSiswa',
        ));
    }
}