<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Guru;
use App\Models\AbsenGuru;
use Illuminate\Http\Request;

class AbsenScanController extends Controller
{
    public function showForm($nip)
    {
        $guru = Guru::where('nip', $nip)->firstOrFail();

        $sudahAbsen = AbsenGuru::where('guru_id', $guru->id)
            ->whereDate('tanggal', today())
            ->exists();

        $dataAbsen = AbsenGuru::where('guru_id', $guru->id)
            ->whereDate('tanggal', today())
            ->first();

        return view('absen_guru.scan_form', compact('guru', 'sudahAbsen', 'dataAbsen'));
    }

    public function submitForm(Request $request, $nip)
    {
        $guru = Guru::where('nip', $nip)->firstOrFail();

        $sudahAbsen = AbsenGuru::where('guru_id', $guru->id)
            ->whereDate('tanggal', today())
            ->exists();

        if ($sudahAbsen) {
            return redirect()->route('absen.scan', $nip)
                ->with('error', 'Anda sudah melakukan absensi hari ini!');
        }

        AbsenGuru::create([
            'guru_id'   => $guru->id,
            'tanggal'   => today()->toDateString(),
            'status'    => 'hadir',
            'jam_masuk' => now()->format('H:i'),
        ]);

        return redirect()->route('absen.scan', $nip)
            ->with('success', 'Absensi berhasil dicatat! ✅');
    }
}