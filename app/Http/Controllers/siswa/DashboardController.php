<?php

namespace App\Http\Controllers\siswa;

use App\Http\Controllers\Controller;
use App\Models\Guru;
use App\Models\Siswa;
use App\Models\AbsenGuru;
use App\Models\AbsenSiswa;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today()->toDateString();

        $totalGuru  = Guru::count();
        $totalSiswa = Siswa::count();

        $hadirHariIni = AbsenGuru::where('tanggal', $today)->where('status', 'hadir')->count()
                      + AbsenSiswa::where('tanggal', $today)->where('status', 'hadir')->count();

        $alphaHariIni = AbsenGuru::where('tanggal', $today)->where('status', 'alpha')->count()
                      + AbsenSiswa::where('tanggal', $today)->where('status', 'alpha')->count();

        $absenGuru = AbsenGuru::with('guru')
            ->where('tanggal', $today)
            ->latest()
            ->take(10)
            ->get()
            ->map(fn($a) => [
                'nama'      => $a->guru->nama ?? '-',
                'kelas'     => $a->guru->mata_pelajaran ?? '-',
                'tipe'      => 'Guru',
                'jam_masuk' => $a->jam_masuk,
                'status'    => $a->status,
            ]);

        $absenSiswa = AbsenSiswa::with('siswa')
            ->where('tanggal', $today)
            ->latest()
            ->take(10)
            ->get()
            ->map(fn($a) => [
                'nama'      => $a->siswa->nama ?? '-',
                'kelas'     => $a->siswa->kelas ?? '-',
                'tipe'      => 'Siswa',
                'jam_masuk' => $a->jam_masuk,
                'status'    => $a->status,
            ]);

        $absensiTerbaru = $absenGuru->concat($absenSiswa)->sortByDesc('jam_masuk')->take(10)->values();

        return view('siswa.dashboard', compact(
            'totalGuru',
            'totalSiswa',
            'hadirHariIni',
            'alphaHariIni',
            'absensiTerbaru',
        ));
    }
}