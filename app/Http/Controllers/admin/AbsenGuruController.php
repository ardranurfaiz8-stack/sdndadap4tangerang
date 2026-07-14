<?php

namespace App\Http\Controllers\admin; // ← sesuai folder

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Guru;
use App\Models\AbsenGuru;

class AbsenGuruController extends Controller
{
    public function index(Request $request)
    {
        $today    = $request->get('tanggal', now()->toDateString());
        $guruList = Guru::orderBy('nama')->get();

        $selectedGuruId = $request->get('guru_id', $guruList->first()?->id);
        $selectedGuru   = $guruList->firstWhere('id', $selectedGuruId) ?? $guruList->first();

        $query = AbsenGuru::with('guru')->whereDate('tanggal', $today);

        if ($request->filled('search')) {
            $query->whereHas('guru', fn($q) =>
                $q->where('nama', 'like', '%'.$request->search.'%')
            );
        }

        $absensi = $query->orderBy('jam_masuk')->paginate(20);

        $logScanHariIni = AbsenGuru::with('guru')
            ->whereDate('tanggal', now()->toDateString())
            ->whereIn('status', ['hadir', 'Hadir'])
            ->orderBy('jam_masuk')
            ->get();

        $hadirHariIni = AbsenGuru::whereDate('tanggal', $today)->whereIn('status', ['hadir', 'Hadir'])->count();
        $izinHariIni  = AbsenGuru::whereDate('tanggal', $today)->whereIn('status', ['izin', 'Izin'])->count();
        $sakitHariIni = AbsenGuru::whereDate('tanggal', $today)->whereIn('status', ['sakit', 'Sakit'])->count();
        $alphaHariIni = AbsenGuru::whereDate('tanggal', $today)->whereIn('status', ['alpha', 'Alpha'])->count();

        return view('guru.absen_guru', compact(
            'guruList', 'selectedGuru',
            'absensi', 'logScanHariIni',
            'hadirHariIni', 'izinHariIni', 'sakitHariIni', 'alphaHariIni',
            'today'
        ));
    }

    public function create()
    {
        $guruList = Guru::orderBy('nama')->get();
        return view('absen_guru.create', compact('guruList'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'guru_id'    => 'required|exists:gurus,id',
            'tanggal'    => 'required|date',
            'status'     => 'required|in:hadir,izin,sakit,alpha',
            'jam_masuk'  => 'nullable|date_format:H:i',
            'jam_keluar' => 'nullable|date_format:H:i',
            'keterangan' => 'nullable|string|max:255',
        ]);

        $exists = AbsenGuru::where('guru_id', $request->guru_id)
            ->whereDate('tanggal', $request->tanggal)
            ->exists();

        if ($exists) {
            return back()->with('error', 'Absensi guru ini sudah tercatat untuk tanggal tersebut.');
        }

        AbsenGuru::create($request->only([
            'guru_id', 'tanggal', 'status', 'jam_masuk', 'jam_keluar', 'keterangan'
        ]));

        return redirect()->route('guru.absen_guru.index')
            ->with('success', 'Absensi guru berhasil disimpan! ✅');
    }

    public function edit($id)
    {
        $absensi  = AbsenGuru::findOrFail($id);
        $guruList = Guru::orderBy('nama')->get();
        return view('absen_guru.edit', compact('absensi', 'guruList'));
    }

    public function update(Request $request, $id)
    {
        $absen = AbsenGuru::findOrFail($id);

        $request->validate([
            'guru_id'    => 'required|exists:gurus,id',
            'tanggal'    => 'required|date',
            'status'     => 'required|in:hadir,izin,sakit,alpha',
            'jam_masuk'  => 'nullable|date_format:H:i',
            'jam_keluar' => 'nullable|date_format:H:i',
            'keterangan' => 'nullable|string|max:255',
        ]);

        $absen->update($request->only([
            'guru_id', 'tanggal', 'status', 'jam_masuk', 'jam_keluar', 'keterangan'
        ]));

        return redirect()->route('guru.absen_guru.index')
            ->with('success', 'Absensi guru berhasil diperbarui! ✅');
    }

    public function destroy($id)
    {
        AbsenGuru::findOrFail($id)->delete();
        return back()->with('success', 'Absensi guru berhasil dihapus! 🗑️');
    }
}