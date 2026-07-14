<?php

namespace App\Http\Controllers\guru;

use App\Http\Controllers\Controller;
use App\Models\Guru;
use App\Models\Siswa;
use App\Models\AbsenGuru;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user  = Auth::user();
        $guru  = Guru::where('user_id', $user->id)->first();
        
        // Fallback: Jika guru belum ter-link user_id, coba cari berdasarkan nama
        if (!$guru) {
            // Hilangkan kata "Guru_" jika ada di nama user
            $namaClean = str_replace('Guru_', '', $user->name);
            $guru = Guru::where('nama', 'like', "%{$namaClean}%")->first();
        }

        $today = Carbon::today()->toDateString();
        $bulan = Carbon::now()->month;
        $tahun = Carbon::now()->year;

        $absenHariIni = null;
        $rekapBulan   = ['hadir' => 0, 'sakit' => 0, 'izin' => 0, 'alpha' => 0];
        $absensiTerbaru = collect();
        $persentaseHadir = 0;

        if ($guru) {
            $absenHariIni = AbsenGuru::where('guru_id', $guru->id)
                ->whereDate('tanggal', $today)
                ->first();

            $absenBulan = AbsenGuru::where('guru_id', $guru->id)
                ->whereMonth('tanggal', $bulan)
                ->whereYear('tanggal', $tahun)
                ->get();

            $rekapBulan = [
                'hadir' => $absenBulan->whereIn('status', ['Hadir', 'hadir'])->count(),
                'sakit' => $absenBulan->whereIn('status', ['Sakit', 'sakit'])->count(),
                'izin'  => $absenBulan->whereIn('status', ['Izin', 'izin'])->count(),
                'alpha' => $absenBulan->whereIn('status', ['Alpha', 'alpha'])->count(),
            ];

            $absensiTerbaru = AbsenGuru::where('guru_id', $guru->id)
                ->orderByDesc('tanggal')
                ->limit(10)
                ->get();

            $totalAbsen = array_sum($rekapBulan);
            $persentaseHadir = $totalAbsen > 0
                ? round(($rekapBulan['hadir'] / $totalAbsen) * 100)
                : 0;
        }

        $hariKerja = $this->hitungHariKerja($bulan, $tahun);

        return view('guru.dashboard', compact(
            'rekapBulan', 'absenHariIni', 'absensiTerbaru',
            'persentaseHadir', 'hariKerja'
        ));
    }

    private function hitungHariKerja(int $bulan, int $tahun): int
    {
        $start = Carbon::create($tahun, $bulan, 1);
        $end   = $start->copy()->endOfMonth();
        $count = 0;
        while ($start->lte($end)) {
            if (!$start->isWeekend()) $count++;
            $start->addDay();
        }
        return $count;
    }
}
