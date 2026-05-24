<?php

namespace App\Http\Controllers\guru;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Siswa;

class ProfilsiswaController extends Controller
{
    public function index(Request $request)
    {
        $kelasList = ['1A','1B','2A','2B','3A','3B','4A','4B','5A','5B','6A','6B'];
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

        return view('guru.profil_siswa', compact(
            'siswa', 'kelasList', 'totalSiswa',
            'siswaLaki', 'siswaPerempuan', 'jumlahKelas'
        ));
    }

    public function create()
    {
        $kelasList = ['1A','1B','2A','2B','3A','3B','4A','4B','5A','5B','6A','6B'];
        return view('profil_siswa.create', compact('kelasList'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama'          => 'required|string|max:100',
            'nis'           => 'required|string|max:20|unique:siswas,nis',
            'kelas'         => 'required|string|max:5',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'tanggal_lahir' => 'nullable|date',
            'tempat_lahir'  => 'nullable|string|max:100',
            'nama_orang_tua'=> 'nullable|string|max:100',
            'no_telp'       => 'nullable|string|max:15',
            'alamat'        => 'nullable|string',
        ]);

        Siswa::create($request->only([
            'nama', 'nis', 'kelas', 'jenis_kelamin',
            'tanggal_lahir', 'tempat_lahir', 'nama_orang_tua', 'no_telp', 'alamat'
        ]));

        return redirect()->route('guru.profil_siswa.index')
            ->with('success', 'Data siswa berhasil ditambahkan! ✅');
    }

    public function edit($id)
    {
        $siswa     = Siswa::findOrFail($id);
        $kelasList = ['1A','1B','2A','2B','3A','3B','4A','4B','5A','5B','6A','6B'];
        return view('profil_siswa.edit', compact('siswa', 'kelasList'));
    }

    public function update(Request $request, $id)
    {
        $siswa = Siswa::findOrFail($id);

        $request->validate([
            'nama'          => 'required|string|max:100',
            'nis'           => 'required|string|max:20|unique:siswas,nis,'.$id,
            'kelas'         => 'required|string|max:5',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'tanggal_lahir' => 'nullable|date',
            'tempat_lahir'  => 'nullable|string|max:100',
            'nama_orang_tua'=> 'nullable|string|max:100',
            'no_telp'       => 'nullable|string|max:15',
            'alamat'        => 'nullable|string',
        ]);

        $siswa->update($request->only([
            'nama', 'nis', 'kelas', 'jenis_kelamin',
            'tanggal_lahir', 'tempat_lahir', 'nama_orang_tua', 'no_telp', 'alamat'
        ]));

        return redirect()->route('guru.profil_siswa.index')
            ->with('success', 'Data siswa berhasil diperbarui! ✅');
    }

    public function destroy($id)
    {
        $siswa = Siswa::findOrFail($id);

        if ($siswa->absensi()->count() > 0) {
            return back()->with('error', 'Siswa ini masih memiliki data absensi, tidak dapat dihapus.');
        }

        $siswa->delete();
        return back()->with('success', 'Data siswa berhasil dihapus! 🗑️');
    }
}