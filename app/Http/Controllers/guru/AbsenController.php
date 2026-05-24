<?php

namespace App\Http\Controllers\guru;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AbsenGuru;
use App\Models\AbsenSiswa;

/**
 * AbsenController
 * Controller dasar yang menangani logika absensi bersama
 * (digunakan jika ada fitur scan QR terpadu guru + siswa).
 * AbsenguruController dan AbsensiswaController extends ini atau Controller biasa.
 */
class AbsenController extends Controller
{
    /**
     * Proses scan QR terpadu — deteksi otomatis guru atau siswa.
     * Dipanggil dari endpoint AJAX: POST /guru/scan-qr
     * Payload: { type: 'guru'|'siswa', kode: 'NIP atau NIS' }
     */
    public function scanQR(Request $request)
    {
        $guruList     = collect([]); // kosong dulu sebelum ada model
        $selectedGuru = null;
        
        return view('guru.absen_guru', [
        'guruList'       => $guruList,
        'selectedGuru'   => $selectedGuru,
        'absensi'        => collect([]),
        'logScanHariIni' => collect([]),
        'hadirHariIni'   => 0,
        'izinHariIni'    => 0,
        'sakitHariIni'   => 0,
        'alphaHariIni'   => 0,
        'today'          => now()->toDateString(),
    ]);

        $today = now()->toDateString();
        $jam   = now()->format('H:i');

        if ($request->type === 'guru') {
            $model  = \App\Models\Guru::where('nip', $request->kode)->first();
            $fkKey  = 'guru_id';
            $AbsenM = AbsenGuru::class;
            $label  = 'Guru';
        } else {
            $model  = \App\Models\Siswa::where('nis', $request->kode)->first();
            $fkKey  = 'siswa_id';
            $AbsenM = AbsenSiswa::class;
            $label  = 'Siswa';
        }

        if (!$model) {
            return response()->json([
                'success' => false,
                'message' => "$label dengan kode {$request->kode} tidak ditemukan.",
            ], 404);
        }

        // Cek sudah absen hari ini
        $exists = $AbsenM::where($fkKey, $model->id)->whereDate('tanggal', $today)->exists();

        if ($exists) {
            return response()->json([
                'success' => false,
                'message' => "{$model->nama} sudah tercatat absen hari ini.",
                'nama'    => $model->nama,
            ]);
        }

        // Catat absensi
        $AbsenM::create([
            $fkKey      => $model->id,
            'tanggal'   => $today,
            'status'    => 'Hadir',
            'jam_masuk' => $jam,
        ]);

        return response()->json([
            'success'   => true,
            'message'   => "{$model->nama} berhasil absen pukul $jam",
            'nama'      => $model->nama,
            'jam_masuk' => $jam,
            'tipe'      => $label,
        ]);
    }

    /**
     * Rekap ringkas untuk widget dashboard.
     * GET /guru/api/rekap-hari-ini
     */
    public function rekapHariIni()
    {
        $today = now()->toDateString();

        return response()->json([
            'guru' => [
                'hadir' => AbsenGuru::whereDate('tanggal', $today)->where('status', 'Hadir')->count(),
                'izin'  => AbsenGuru::whereDate('tanggal', $today)->where('status', 'Izin')->count(),
                'sakit' => AbsenGuru::whereDate('tanggal', $today)->where('status', 'Sakit')->count(),
                'alpha' => AbsenGuru::whereDate('tanggal', $today)->where('status', 'Alpha')->count(),
            ],
            'siswa' => [
                'hadir' => AbsenSiswa::whereDate('tanggal', $today)->where('status', 'Hadir')->count(),
                'izin'  => AbsenSiswa::whereDate('tanggal', $today)->where('status', 'Izin')->count(),
                'sakit' => AbsenSiswa::whereDate('tanggal', $today)->where('status', 'Sakit')->count(),
                'alpha' => AbsenSiswa::whereDate('tanggal', $today)->where('status', 'Alpha')->count(),
            ],
        ]);
    }
}