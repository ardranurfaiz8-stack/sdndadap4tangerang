<?php

namespace App\Http\Controllers\guru;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Guru;

class ProfilguruController extends Controller
{
    public function index(Request $request)
    {
        $query = Guru::query();

    if ($request->filled('search')) {
        $query->where('nama', 'like', '%'.$request->search.'%')
              ->orWhere('nip', 'like', '%'.$request->search.'%');
    }

    $guru          = $query->orderBy('nama')->paginate(20);
    $totalGuru     = Guru::count();
    $guruLaki      = Guru::where('jenis_kelamin', 'Laki-laki')->count();
    $guruPerempuan = Guru::where('jenis_kelamin', 'Perempuan')->count();
    $guruAktif     = $totalGuru;

    return view('guru.profil_guru', compact(
        'guru', 'totalGuru', 'guruLaki', 'guruPerempuan', 'guruAktif'
        ));
    }

    public function create()
    {
        return view('profil_guru.create');
    }

    public function store(Request $request)

    {
    $request->validate([
        'nama'          => 'required|string|max:100',
        'nip'           => 'nullable|string|max:20|unique:gurus,nip',
        'jabatan'       => 'nullable|string|max:100',
        'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
        'no_telp'       => 'nullable|string|max:15', // ← no_hp → no_telp
        'email'         => 'nullable|email|max:100',
        'alamat'        => 'nullable|string',
        'mata_pelajaran'=> 'nullable|string|max:100',
        'tempat_lahir'  => 'nullable|string|max:100',
        'tanggal_lahir' => 'nullable|date',
    ]);

     Guru::create($request->only([
        'nama', 'nip', 'jenis_kelamin', 'no_telp', // ← no_hp → no_telp
        'email', 'alamat', 'mata_pelajaran', 'tempat_lahir', 'tanggal_lahir'
    ]));

    return back()->with('success', 'Data guru berhasil ditambahkan! ✅');
}


    public function update(Request $request, $id)
{
    $guru = Guru::findOrFail($id);

    $request->validate([
        'nama'          => 'required|string|max:100',
        'nip'           => 'nullable|string|max:20|unique:gurus,nip,'.$id,
        'jabatan'       => 'nullable|string|max:100',
        'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
        'no_telp'       => 'nullable|string|max:15', // ← no_hp → no_telp
        'email'         => 'nullable|email|max:100',
        'alamat'        => 'nullable|string',
        'mata_pelajaran'=> 'nullable|string|max:100',
        'tempat_lahir'  => 'nullable|string|max:100',
        'tanggal_lahir' => 'nullable|date',
    ]);

    $guru->update($request->only([
        'nama', 'nip', 'jenis_kelamin', 'no_telp', // ← no_hp → no_telp
        'email', 'alamat', 'mata_pelajaran', 'tempat_lahir', 'tanggal_lahir'
    ]));

    return back()->with('success', 'Data guru berhasil diperbarui! ✅');
}
    public function edit($id)
    {
        $guru = Guru::findOrFail($id);
        return view('profil_guru.edit', compact('guru'));
    }

    public function destroy($id)
    {
        $guru = Guru::findOrFail($id);

        if ($guru->absensi()->count() > 0) {
            return back()->with('error', 'Guru ini masih memiliki data absensi, tidak dapat dihapus.');
        }

        $guru->delete();
        return back()->with('success', 'Data guru berhasil dihapus! 🗑️');
    }
}