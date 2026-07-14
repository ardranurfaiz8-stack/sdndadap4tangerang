<?php

namespace App\Http\Controllers\siswa;

use App\Http\Controllers\Controller;
use App\Models\Guru;
use App\Models\Siswa;
use App\Models\AbsenGuru;
use App\Models\AbsenSiswa;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Tambahkan pengaman jika $user belum ter-load (misal session expired)
        if (!$user) {
            return redirect()->route('login');
        }

        $siswa = Siswa::where('user_id', $user->id)->first();
        if (!$siswa) {
            $namaClean = str_replace('Siswa_', '', $user->name);
            $siswa = Siswa::where('nama', 'like', "%{$namaClean}%")->first();
        }

        $today = Carbon::today()->toDateString();
        $bulan = Carbon::now()->month;
        $tahun = Carbon::now()->year;

        $rekapBulan = ['hadir' => 0, 'sakit' => 0, 'izin' => 0, 'alpha' => 0];
        $absensiTerbaru = collect();

        if ($siswa) {
            $absenBulan = AbsenSiswa::where('siswa_id', $siswa->id)
                ->whereMonth('tanggal', $bulan)
                ->whereYear('tanggal', $tahun)
                ->get();

            $rekapBulan = [
                'hadir' => $absenBulan->whereIn('status', ['Hadir', 'hadir'])->count(),
                'sakit' => $absenBulan->whereIn('status', ['Sakit', 'sakit'])->count(),
                'izin'  => $absenBulan->whereIn('status', ['Izin', 'izin'])->count(),
                'alpha' => $absenBulan->whereIn('status', ['Alpha', 'alpha'])->count(),
            ];

            $absensiTerbaru = AbsenSiswa::with('siswa')
                ->where('siswa_id', $siswa->id)
                ->latest('tanggal')
                ->latest('jam_masuk')
                ->take(10)
                ->get()
                ->map(fn($a) => [
                    'nama'      => $a->siswa->nama ?? '-',
                    'kelas'     => $a->siswa->kelas ?? '-',
                    'tipe'      => 'Siswa',
                    'jam_masuk' => $a->jam_masuk,
                    'status'    => $a->status,
                ]);
        }

        return view('siswa.dashboard', compact(
            'rekapBulan',
            'absensiTerbaru'
        ));
    }
}
