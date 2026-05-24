<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\AbsenSiswa;
use App\Models\AbsenGuru;
use App\Models\Siswa;
use App\Models\Guru;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class RekapController extends Controller
{
    public function index(Request $request)
    {
        $tipe  = $request->get('tipe', 'siswa');
        $bulan = (int) $request->get('bulan', date('n'));
        $tahun = (int) $request->get('tahun', date('Y'));

        $startDate = Carbon::create($tahun, $bulan, 1)->startOfMonth()->toDateString();
        $endDate   = Carbon::create($tahun, $bulan, 1)->endOfMonth()->toDateString();
        $totalHari = Carbon::create($tahun, $bulan, 1)->daysInMonth;

        // ===== SISWA =====
        if ($tipe === 'siswa') {
            $list = Siswa::orderBy('kelas')->orderBy('nama')->get();

            $rekapDetail = $list->map(function ($s) use ($startDate, $endDate, $totalHari) {
                $absensi = AbsenSiswa::where('siswa_id', $s->id)
                    ->whereBetween('tanggal', [$startDate, $endDate])->get();

                $hadir = $absensi->where('status', 'hadir')->count();
                $sakit = $absensi->where('status', 'sakit')->count();
                $izin  = $absensi->where('status', 'izin')->count();
                $alpha = $absensi->where('status', 'alpha')->count();
                $total = max($absensi->count(), 1);
                $pct   = round(($hadir / $total) * 100);

                return [
                    'nama'         => $s->nama,
                    'kelas_jabatan'=> $s->kelas ?? '-',
                    'hadir'        => $hadir,
                    'sakit'        => $sakit,
                    'izin'         => $izin,
                    'alpha'        => $alpha,
                    'pct_hadir'    => $pct,
                ];
            })->values();

        // ===== GURU =====
        } else {
            $list = Guru::orderBy('nama')->get();

            $rekapDetail = $list->map(function ($g) use ($startDate, $endDate) {
                $absensi = AbsenGuru::where('guru_id', $g->id)
                    ->whereBetween('tanggal', [$startDate, $endDate])->get();

                $hadir = $absensi->where('status', 'hadir')->count();
                $sakit = $absensi->where('status', 'sakit')->count();
                $izin  = $absensi->where('status', 'izin')->count();
                $alpha = $absensi->where('status', 'alpha')->count();
                $total = max($absensi->count(), 1);
                $pct   = round(($hadir / $total) * 100);

                return [
                    'nama'         => $g->nama,
                    'kelas_jabatan'=> $g->mata_pelajaran ?? '-',
                    'hadir'        => $hadir,
                    'sakit'        => $sakit,
                    'izin'         => $izin,
                    'alpha'        => $alpha,
                    'pct_hadir'    => $pct,
                ];
            })->values();
        }

        // ===== RATA-RATA =====
        $totalRows  = $rekapDetail->count();
        $rataHadir  = $totalRows ? round($rekapDetail->avg('pct_hadir')) : 0;
        $totalAbsen = $rekapDetail->sum(fn($r) => $r['hadir'] + $r['sakit'] + $r['izin'] + $r['alpha']);
        $rataSakit  = $totalAbsen ? round($rekapDetail->sum('sakit')  / $totalAbsen * 100) : 0;
        $rataIzin   = $totalAbsen ? round($rekapDetail->sum('izin')   / $totalAbsen * 100) : 0;
        $rataAlpha  = $totalAbsen ? round($rekapDetail->sum('alpha')  / $totalAbsen * 100) : 0;

        // ===== TERENDAH =====
        $terendah = $rekapDetail->sortBy('pct_hadir')->take(5)->values();

        // ===== TREN BULANAN (12 bulan terakhir) =====
        $trendBulanan = collect();
        $bulanNames   = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'];
        for ($m = 1; $m <= 12; $m++) {
            $sd = Carbon::create($tahun, $m, 1)->startOfMonth()->toDateString();
            $ed = Carbon::create($tahun, $m, 1)->endOfMonth()->toDateString();

            if ($tipe === 'siswa') {
                $total = AbsenSiswa::whereBetween('tanggal', [$sd, $ed])->count();
                $hadir = AbsenSiswa::whereBetween('tanggal', [$sd, $ed])->where('status', 'hadir')->count();
            } else {
                $total = AbsenGuru::whereBetween('tanggal', [$sd, $ed])->count();
                $hadir = AbsenGuru::whereBetween('tanggal', [$sd, $ed])->where('status', 'hadir')->count();
            }

            $trendBulanan->push([
                'bulan' => $bulanNames[$m - 1],
                'pct'   => $total > 0 ? round($hadir / $total * 100) : 0,
            ]);
        }

        return view('admin.rekap', compact(
            'rekapDetail', 'terendah', 'trendBulanan',
            'rataHadir', 'rataSakit', 'rataIzin', 'rataAlpha',
            'tipe', 'bulan', 'tahun'
        ));
    }

    public function export(Request $request)
    {
        // Redirect kembali dengan notif (implementasi export bisa pakai maatwebsite/excel)
        return redirect()->back()->with('success', 'Fitur export sedang dalam pengembangan.');
    }
}