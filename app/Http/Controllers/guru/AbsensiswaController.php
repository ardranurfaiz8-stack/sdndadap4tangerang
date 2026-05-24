<?php

namespace App\Http\Controllers\guru;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Siswa;
use App\Models\AbsenSiswa;

class AbsensiswaController extends Controller
{
    public function index(Request $request)
    {
        $today     = $request->get('tanggal', now()->toDateString());
        $kelas     = $request->get('kelas', '');
        $status    = $request->get('status', '');
        $kelasList = ['1A','1B','2A','2B','3A','3B','4A','4B','5A','5B','6A','6B'];

        $query = AbsenSiswa::with('siswa')->whereDate('tanggal', $today);

        if ($kelas)  $query->whereHas('siswa', fn($q) => $q->where('kelas', $kelas));
        if ($status) $query->where('status', $status);

        if ($request->filled('search')) {
            $query->whereHas('siswa', fn($q) =>
                $q->where('nama', 'like', '%'.$request->search.'%')
                  ->orWhere('nis', 'like', '%'.$request->search.'%')
            );
        }

        $absensi   = $query->orderBy('jam_masuk')->paginate(20);
        $siswaList = Siswa::orderBy('nama')->get();

        $logScanSiswa = AbsenSiswa::with('siswa')
            ->whereDate('tanggal', now()->toDateString())
            ->where('status', 'hadir')
            ->orderBy('jam_masuk')
            ->get();

        $hadirHariIni = AbsenSiswa::whereDate('tanggal', $today)->where('status', 'hadir')->count();
        $izinHariIni  = AbsenSiswa::whereDate('tanggal', $today)->where('status', 'izin')->count();
        $sakitHariIni = AbsenSiswa::whereDate('tanggal', $today)->where('status', 'sakit')->count();
        $alphaHariIni = AbsenSiswa::whereDate('tanggal', $today)->where('status', 'alpha')->count();

        return view('guru.absen_siswa', compact(
            'absensi', 'siswaList', 'kelasList', 'logScanSiswa',
            'hadirHariIni', 'izinHariIni', 'sakitHariIni', 'alphaHariIni',
            'today'
        ));
    }

    public function create()
    {
        $siswaList = Siswa::orderBy('nama')->get();
        $kelasList = ['1A','1B','2A','2B','3A','3B','4A','4B','5A','5B','6A','6B'];
        return view('absen_siswa.create', compact('siswaList', 'kelasList'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'siswa_id'   => 'required|exists:siswas,id',
            'tanggal'    => 'required|date',
            'status'     => 'required|in:hadir,izin,sakit,alpha',
            'jam_masuk'  => 'nullable|date_format:H:i',
            'keterangan' => 'nullable|string|max:255',
        ]);

        $exists = AbsenSiswa::where('siswa_id', $request->siswa_id)
            ->whereDate('tanggal', $request->tanggal)->exists();

        if ($exists) {
            return back()->with('error', 'Absensi siswa ini sudah tercatat untuk tanggal tersebut.');
        }

        AbsenSiswa::create($request->only([
            'siswa_id', 'tanggal', 'status', 'jam_masuk', 'keterangan'
        ]));

        return redirect()->route('guru.absen_siswa.index')
            ->with('success', 'Absensi siswa berhasil disimpan! ✅');
    }

    public function edit($id)
    {
        $absensi   = AbsenSiswa::with('siswa')->findOrFail($id);
        $siswaList = Siswa::orderBy('nama')->get();
        return view('absen_siswa.edit', compact('absensi', 'siswaList'));
    }

    public function update(Request $request, $id)
    {
        $absen = AbsenSiswa::findOrFail($id);

        $request->validate([
            'siswa_id'   => 'required|exists:siswas,id',
            'tanggal'    => 'required|date',
            'status'     => 'required|in:hadir,izin,sakit,alpha',
            'jam_masuk'  => 'nullable|date_format:H:i',
            'keterangan' => 'nullable|string|max:255',
        ]);

        $absen->update($request->only([
            'siswa_id', 'tanggal', 'status', 'jam_masuk', 'keterangan'
        ]));

        return redirect()->route('guru.absen_siswa.index')
            ->with('success', 'Absensi siswa berhasil diperbarui! ✅');
    }

    public function destroy($id)
    {
        AbsenSiswa::findOrFail($id)->delete();
        return back()->with('success', 'Absensi siswa berhasil dihapus! 🗑️');
    }

    public function siswaByKelas(Request $request)
    {
        $siswa = Siswa::where('kelas', $request->kelas)
            ->orderBy('nama')
            ->get(['id', 'nama', 'nis', 'kelas']);
        return response()->json($siswa);
    }
}