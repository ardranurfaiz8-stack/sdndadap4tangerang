<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\AbsenSiswa;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class AbsensiswaController extends Controller
{
    public function index(Request $request)
    {
        $tanggal = $request->get('tanggal', Carbon::today()->toDateString());
        $kelas   = $request->get('kelas');
        $search  = $request->get('search');

        // Semua kelas unik untuk filter dropdown
        $kelasList = Siswa::whereNotNull('kelas')->distinct()->orderBy('kelas')->pluck('kelas');

        // Query siswa
        $query = Siswa::orderBy('kelas')->orderBy('nama');
        if ($kelas)  $query->where('kelas', $kelas);
        if ($search) $query->where('nama', 'like', "%{$search}%");
        $siswaList = $query->get();

        // Semua absensi hari ini → map by siswa_id
        $absensiMap = AbsenSiswa::where('tanggal', $tanggal)
            ->whereIn('siswa_id', $siswaList->pluck('id'))
            ->get()
            ->keyBy('siswa_id');

        // Summary
        $summary = [
            'hadir' => $absensiMap->where('status','hadir')->count(),
            'sakit' => $absensiMap->where('status','sakit')->count(),
            'izin'  => $absensiMap->where('status','izin')->count(),
            'alpha' => $absensiMap->where('status','alpha')->count(),
        ];

        // Untuk modal tambah
        $semuaSiswa = Siswa::orderBy('nama')->get();

        return view('absen_siswa.index', compact(
            'siswaList','absensiMap','summary','tanggal','kelasList','semuaSiswa'
        ));
    }

    public function store(Request $request)
    {
        $tanggal = $request->get('tanggal', Carbon::today()->toDateString());

        // BULK SAVE dari tabel
        if ($request->has('bulk_save')) {
            $statuses = $request->get('statuses', []);
            foreach ($statuses as $siswaId => $status) {
                AbsenSiswa::updateOrCreate(
                    ['siswa_id' => $siswaId, 'tanggal' => $tanggal],
                    ['status'   => $status]
                );
            }
            return redirect()->route('admin.absen-siswa.index', ['tanggal' => $tanggal])
                ->with('success', 'Absensi siswa berhasil disimpan!');
        }

        // SEMUA HADIR
        if ($request->has('bulk_hadir')) {
            $kelas = $request->get('kelas');
            $query = Siswa::query();
            if ($kelas) $query->where('kelas', $kelas);
            $siswaList = $query->get();
            foreach ($siswaList as $siswa) {
                AbsenSiswa::updateOrCreate(
                    ['siswa_id' => $siswa->id, 'tanggal' => $tanggal],
                    ['status'   => 'hadir']
                );
            }
            return redirect()->route('admin.absen-siswa.index', ['tanggal' => $tanggal])
                ->with('success', 'Semua siswa ditandai Hadir!');
        }

        // TAMBAH SATU SISWA
        $request->validate([
            'siswa_id'   => 'required|exists:siswas,id',
            'status'     => 'required|in:hadir,sakit,izin,alpha',
            'keterangan' => 'nullable|string',
        ]);

        AbsenSiswa::updateOrCreate(
            ['siswa_id' => $request->siswa_id, 'tanggal' => $tanggal],
            ['status'   => $request->status, 'keterangan' => $request->keterangan]
        );

        return redirect()->route('admin.absen-siswa.index', ['tanggal' => $tanggal])
            ->with('success', 'Absensi siswa berhasil disimpan!');
    }

    public function show($siswaId)
    {
        $siswa   = Siswa::findOrFail($siswaId);
        $absensi = AbsenSiswa::where('siswa_id', $siswaId)
            ->orderBy('tanggal', 'desc')->take(30)->get();
        return view('absen_siswa.show', compact('siswa', 'absensi'));
    }

    public function edit(AbsenSiswa $absenSiswa)
    {
        $absensi = $absenSiswa;
        $absensi->load('siswa');
        $siswaList = Siswa::orderBy('nama')->get();
        return view('absen_siswa.edit', compact('absensi', 'siswaList'));
    }

    public function update(Request $request, AbsenSiswa $absenSiswa)
    {
        $request->validate([
            'status'     => 'required|in:hadir,sakit,izin,alpha',
            'keterangan' => 'nullable|string',
            'tanggal'    => 'required|date',
        ]);

        $absenSiswa->update([
            'status'     => $request->status,
            'keterangan' => $request->keterangan,
            'tanggal'    => $request->tanggal,
        ]);

        return redirect()->route('admin.absen-siswa.index', ['tanggal' => $request->tanggal])
            ->with('success', 'Absensi berhasil diperbarui!');
    }

    public function destroy(AbsenSiswa $absenSiswa)
    {
        $tanggal = $absenSiswa->tanggal;
        $absenSiswa->delete();
        return redirect()->route('admin.absen-siswa.index', ['tanggal' => $tanggal])
            ->with('success', 'Data absensi berhasil dihapus!');
    }
}