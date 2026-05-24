<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Siswa;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class SiswaController extends Controller
{
    public function index(Request $request)
    {
        $search  = $request->get('search');
        $siswas  = Siswa::query()
            ->when($search, fn($q) => $q->where('nama', 'like', "%$search%")
                                         ->orWhere('nis', 'like', "%$search%"))
            ->orderBy('nama')
            ->get();

        $siswaId      = $request->get('siswa_id');
        $selectedSiswa = $siswaId ? Siswa::find($siswaId) : $siswas->first();

        return view('profil_siswa.index', compact('siswas', 'selectedSiswa'));
    }

    public function create()
    {
        return view('profil_siswa.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama'       => 'required|string|max:255',
            'nis'        => 'nullable|string|unique:siswas,nis',
            'user_email' => 'required|email|unique:users,email',
            'password'   => 'required|min:8',
        ]);

        $user = User::create([
            'name'     => $request->nama,
            'email'    => $request->user_email,
            'password' => Hash::make($request->password),
            'role'     => 'siswa',
        ]);

        Siswa::create([
            'user_id'       => $user->id,
            'nama'          => $request->nama,
            'nis'           => $request->nis,
            'email'         => $request->user_email,
            'kelas'         => $request->kelas,
            'jenis_kelamin' => $request->jenis_kelamin,
            'tempat_lahir'  => $request->tempat_lahir,
            'tanggal_lahir' => $request->tanggal_lahir,
            'alamat'        => $request->alamat,
            'no_telp'       => $request->no_telp,
            'nama_orang_tua'=> $request->nama_orang_tua,
        ]);

        return redirect()->route('admin.siswa.index')
            ->with('success', 'Data siswa berhasil ditambahkan!');
    }

    public function show(Siswa $siswa)
    {
        return view('profil_siswa.show', compact('siswa'));
    }

    public function edit(Siswa $siswa)
    {
        return view('profil_siswa.edit', compact('siswa'));
    }

    public function update(Request $request, Siswa $siswa)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'nis'  => 'nullable|string|unique:siswas,nis,' . $siswa->id,
        ]);

        $siswa->update($request->only([
            'nama', 'nis', 'kelas', 'jenis_kelamin',
            'tempat_lahir', 'tanggal_lahir', 'alamat',
            'no_telp', 'email', 'nama_orang_tua',
        ]));

        return redirect()->route('admin.siswa.index', ['siswa_id' => $siswa->id])
            ->with('success', 'Profil ' . $siswa->nama . ' berhasil diperbarui!');
    }

    public function destroy(Siswa $siswa)
    {
        $siswa->user?->delete();
        $siswa->delete();
        return redirect()->route('admin.siswa.index')
            ->with('success', 'Data siswa berhasil dihapus!');
    }
}