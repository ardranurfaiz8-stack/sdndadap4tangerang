<?php

namespace App\Http\Controllers\kepala_sekolah;

use App\Http\Controllers\Controller;
use App\Models\Siswa;
use Illuminate\Http\Request;

class SiswaController extends Controller
{
    public function index(Request $request)
    {
        $kelasList = Siswa::select('kelas')->distinct()->orderBy('kelas')->pluck('kelas');
        $kelas     = $request->get('kelas', '');

        $query = Siswa::query();
        if ($kelas) $query->where('kelas', $kelas);

        if ($request->filled('search')) {
            $query->where(fn($q) =>
                $q->where('nama', 'like', '%'.$request->search.'%')
                  ->orWhere('nis', 'like', '%'.$request->search.'%')
            );
        }

        $siswa          = $query->orderBy('kelas')->orderBy('nama')->paginate(20);
        $totalSiswa     = Siswa::count();
        $siswaLaki      = Siswa::where('jenis_kelamin', 'Laki-laki')->count();
        $siswaPerempuan = Siswa::where('jenis_kelamin', 'Perempuan')->count();
        $jumlahKelas    = Siswa::distinct('kelas')->count('kelas');

        return view('kepala_sekolah.siswa', compact(
            'siswa', 'kelasList', 'totalSiswa',
            'siswaLaki', 'siswaPerempuan', 'jumlahKelas'
        ));
    }
}
