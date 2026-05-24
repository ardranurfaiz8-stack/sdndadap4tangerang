<?php

namespace App\Http\Controllers\kepala_sekolah;

use App\Http\Controllers\Controller;
use App\Models\AbsenSiswa;
use App\Models\Siswa;
use Illuminate\Http\Request;

class AbsenSiswaController extends Controller
{
    public function index(Request $request)
    {
        $today = $request->get('tanggal', now()->toDateString());
        $kelas = $request->get('kelas', '');

        $query = AbsenSiswa::with('siswa')->whereDate('tanggal', $today);

        if ($kelas) {
            $query->whereHas('siswa', fn($q) => $q->where('kelas', $kelas));
        }

        if ($request->filled('search')) {
            $query->whereHas('siswa', fn($q) =>
                $q->where('nama', 'like', '%'.$request->search.'%')
            );
        }

        $absensi = $query->orderBy('created_at')->paginate(20);

        $hadirHariIni = AbsenSiswa::whereDate('tanggal', $today)->where('status', 'Hadir')->count();
        $izinHariIni  = AbsenSiswa::whereDate('tanggal', $today)->where('status', 'Izin')->count();
        $sakitHariIni = AbsenSiswa::whereDate('tanggal', $today)->where('status', 'Sakit')->count();
        $alphaHariIni = AbsenSiswa::whereDate('tanggal', $today)->where('status', 'Alpha')->count();
        $totalSiswa   = Siswa::count();
        $kelasList    = Siswa::select('kelas')->distinct()->orderBy('kelas')->pluck('kelas');

        return view('kepala_sekolah.absen_siswa', compact(
            'absensi', 'today', 'kelas', 'totalSiswa', 'kelasList',
            'hadirHariIni', 'izinHariIni', 'sakitHariIni', 'alphaHariIni'
        ));
    }
}
