<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Guru;
use Illuminate\Http\Request;

class ProfilController extends Controller
{
    public function index(Request $request)
    {
        $gurus        = Guru::orderBy('nama')->get();
        $guruId       = $request->get('guru_id');
        $selectedGuru = $guruId ? Guru::find($guruId) : $gurus->first();

        return view('profil_guru.index', compact('gurus', 'selectedGuru'));
    }

    public function update(Request $request, Guru $guru)
    {
        $request->validate([
            'nama'                => 'required|string|max:255',
            'nip'                 => 'nullable|string|max:30',
            'jenis_kelamin'       => 'nullable|in:L,P',
            'tanggal_lahir'       => 'nullable|date',
            'tempat_lahir'        => 'nullable|string|max:100',
            'mata_pelajaran'      => 'nullable|string|max:100',
            'kelas_pengajar'      => 'nullable|string|max:100',
            'pendidikan_terakhir' => 'nullable|string|max:10',
            'tahun_masuk'         => 'nullable|string|max:4',
            'email'               => 'nullable|email|max:255',
            'no_telp'             => 'nullable|string|max:20',
            'alamat'              => 'nullable|string',
        ]);

        $guru->update($request->only([
            'nama', 'nip', 'jenis_kelamin', 'tanggal_lahir', 'tempat_lahir',
            'mata_pelajaran', 'kelas_pengajar', 'pendidikan_terakhir',
            'tahun_masuk', 'email', 'no_telp', 'alamat',
        ]));

        return redirect()
            ->route('admin.profil.index', ['guru_id' => $guru->id])
            ->with('success', 'Profil ' . $guru->nama . ' berhasil diperbarui!');
    }
}