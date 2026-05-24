<?php

namespace App\Http\Controllers\siswa;

use App\Http\Controllers\Controller;
use App\Models\AbsenSiswa;
use App\Models\Siswa;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AbsensiController extends Controller
{
    public function index()
    {
        $tanggal     = request('tanggal', Carbon::today()->toDateString());
        $kelasFilter = request('kelas', '');
        $search      = request('search', '');

        // Ambil semua siswa dengan filter kelas & search
        $query = Siswa::query();
        if ($kelasFilter) $query->where('kelas', $kelasFilter);
        if ($search)      $query->where('nama', 'like', "%{$search}%");
        $semuaSiswa = $query->orderBy('nama')->get();

        // Ambil absensi di tanggal tersebut
        $absenHari = AbsenSiswa::where('tanggal', $tanggal)
            ->whereIn('siswa_id', $semuaSiswa->pluck('id'))
            ->get()
            ->keyBy('siswa_id');

        // Gabungkan siswa + status absensinya
        $siswas = $semuaSiswa->map(fn($s) => [
            'nama'   => $s->nama,
            'nis'    => $s->nis,
            'kelas'  => $s->kelas,
            'status' => $absenHari->get($s->id)?->status ?? null,
        ])->values()->toArray();

        // Counter per status
        $counter = [
            'hadir' => collect($siswas)->where('status', 'hadir')->count(),
            'sakit' => collect($siswas)->where('status', 'sakit')->count(),
            'izin'  => collect($siswas)->where('status', 'izin')->count(),
            'alpha' => collect($siswas)->where('status', 'alpha')->count(),
        ];

        // Dropdown kelas
        $kelasList = Siswa::select('kelas')->distinct()->orderBy('kelas')->pluck('kelas');

        return view('siswa.absensi', compact(
            'siswas', 'counter', 'kelasList',
            'tanggal', 'kelasFilter', 'search'
        ));
    }

}