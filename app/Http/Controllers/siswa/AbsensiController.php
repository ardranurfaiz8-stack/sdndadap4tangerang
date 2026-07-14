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

        $user = Auth::user();
        $siswaLog = Siswa::where('user_id', $user->id)->first();
        if (!$siswaLog) {
            $namaClean = str_replace('Siswa_', '', $user->name);
            $siswaLog = Siswa::where('nama', 'like', "%{$namaClean}%")->first();
        }

        // Ambil data siswa hanya untuk siswa yang sedang login
        $query = Siswa::query();
        if ($siswaLog) {
            $query->where('id', $siswaLog->id);
        } else {
            $query->where('id', 0); // Jika tidak ditemukan, jangan tampilkan apa-apa
        }

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
            'hadir' => collect($siswas)->whereIn('status', ['hadir', 'Hadir'])->count(),
            'sakit' => collect($siswas)->whereIn('status', ['sakit', 'Sakit'])->count(),
            'izin'  => collect($siswas)->whereIn('status', ['izin', 'Izin'])->count(),
            'alpha' => collect($siswas)->whereIn('status', ['alpha', 'Alpha'])->count(),
        ];

        // Dropdown kelas
        $kelasList = Siswa::select('kelas')->distinct()->orderBy('kelas')->pluck('kelas');

        return view('siswa.absensi', compact(
            'siswas', 'counter', 'kelasList',
            'tanggal', 'kelasFilter', 'search'
        ));
    }

}