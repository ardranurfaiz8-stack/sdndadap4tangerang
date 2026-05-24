<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Guru;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class GuruController extends Controller
{
    public function index(Request $request)
{
    $search = $request->get('search');
    $gurus  = Guru::query()
        ->when($search, fn($q) => $q->where('nama', 'like', "%$search%")
                                     ->orWhere('nip', 'like', "%$search%"))
        ->orderBy('nama')
        ->get();

    $guruId       = $request->get('guru_id');
    $selectedGuru = $guruId ? Guru::find($guruId) : $gurus->first();

    return view('profil_guru.index', compact('gurus', 'selectedGuru'));
}

    public function create()
    {
        return view('profil_guru.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama'     => 'required|string|max:255',
            'nip'      => 'required|string|unique:gurus,nip',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|confirmed|min:8',
        ]);

        $user = User::create([
            'name'     => $request->nama,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => 'guru',
        ]);

        Guru::create([
            ...$request->except(['password', 'password_confirmation', 'email']),
            'user_id' => $user->id,
        ]);

        return redirect()->route('admin.guru.index')
            ->with('success', 'Data guru berhasil ditambahkan!');
    }

    public function show(Guru $guru)
    {
        return view('profil_guru.show', compact('guru'));
    }

    public function edit(Guru $guru)
    {
        return view('profil_guru.edit', compact('guru'));
    }

    public function update(Request $request, Guru $guru)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'nip'  => 'required|string|unique:gurus,nip,' . $guru->id,
        ]);

        $guru->update($request->all());

        return redirect()->route('admin.guru.index')
            ->with('success', 'Data guru berhasil diperbarui!');
    }

    public function destroy(Guru $guru)
    {
        $guru->delete();
        return redirect()->route('admin.guru.index')
            ->with('success', 'Data guru berhasil dihapus!');
    }

    public function profil(Guru $guru)
    {
        $guruList = Guru::orderBy('nama')->get();
        return view('profil_guru.index', compact('guru', 'guruList'));
    }

    public function profilUpdate(Request $request, Guru $guru)
    {
        $guru->update($request->all());
        return redirect()->back()->with('success', 'Profil guru berhasil disimpan!');
    }
}