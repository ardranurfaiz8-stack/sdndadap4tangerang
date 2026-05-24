<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Guru;
use App\Models\Siswa;
use App\Models\AbsenGuru;
use App\Models\AbsenSiswa;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today()->toDateString();

        $totalGuru  = Guru::count();
        $totalSiswa = Siswa::count();

        $hadirHariIni = AbsenSiswa::where('tanggal', $today)->where('status', 'hadir')->count()
                      + AbsenGuru::where('tanggal', $today)->where('status', 'hadir')->count();

        $sakitHariIni = AbsenSiswa::where('tanggal', $today)->where('status', 'sakit')->count()
                      + AbsenGuru::where('tanggal', $today)->where('status', 'sakit')->count();

        $izinHariIni  = AbsenSiswa::where('tanggal', $today)->where('status', 'izin')->count()
                      + AbsenGuru::where('tanggal', $today)->where('status', 'izin')->count();

        $alphaHariIni = AbsenSiswa::where('tanggal', $today)->where('status', 'alpha')->count()
                      + AbsenGuru::where('tanggal', $today)->where('status', 'alpha')->count();

        // Gabungkan absensi terbaru (guru + siswa) hari ini
        $absenSiswaHariIni = AbsenSiswa::with('siswa')
            ->where('tanggal', $today)
            ->latest()
            ->take(10)
            ->get()
            ->map(fn($a) => [
                'nama'          => $a->siswa->nama ?? '-',
                'kelas_jabatan' => $a->siswa->kelas ?? '-',
                'tipe'          => 'Siswa',
                'jam_masuk'     => $a->jam_masuk ?? '-',
                'status'        => $a->status,
            ]);

        $absenGuruHariIni = AbsenGuru::with('guru')
            ->where('tanggal', $today)
            ->latest()
            ->take(10)
            ->get()
            ->map(fn($a) => [
                'nama'          => $a->guru->nama ?? '-',
                'kelas_jabatan' => $a->guru->mata_pelajaran ?? '-',
                'tipe'          => 'Guru',
                'jam_masuk'     => $a->jam_masuk ?? '-',
                'status'        => $a->status,
            ]);

        $absensiTerbaru = collect($absenSiswaHariIni->toArray())
            ->merge(collect($absenGuruHariIni->toArray()))
            ->take(10);
        return view('admin.dashboard', compact(
            'totalGuru',
            'totalSiswa',
            'hadirHariIni',
            'sakitHariIni',
            'izinHariIni',
            'alphaHariIni',
            'absensiTerbaru'
        ));
    }
}