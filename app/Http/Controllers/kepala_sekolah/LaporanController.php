<?php

namespace App\Http\Controllers\kepala_sekolah;

use App\Http\Controllers\Controller;
use App\Models\AbsenGuru;
use App\Models\AbsenSiswa;
use App\Models\Guru;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Carbon\Carbon;

class LaporanController extends Controller
{
    /**
     * Tampilkan halaman laporan lengkap.
     */
    public function index(Request $request)
    {
        $tipe  = $request->get('tipe', 'guru');
        $bulan = $request->get('bulan', Carbon::now()->month);
        $tahun = $request->get('tahun', Carbon::now()->year);
        $kelas = $request->get('kelas', '');

        if ($tipe === 'guru') {
            $laporan   = $this->laporanGuru($bulan, $tahun);
            $title     = 'Laporan Absensi Guru';
        } else {
            $laporan   = $this->laporanSiswa($bulan, $tahun, $kelas);
            $title     = 'Laporan Absensi Siswa';
        }

        $kelasList = Siswa::select('kelas')->distinct()->orderBy('kelas')->pluck('kelas');
        $bulanList = [
            1=>'Januari', 2=>'Februari', 3=>'Maret', 4=>'April',
            5=>'Mei', 6=>'Juni', 7=>'Juli', 8=>'Agustus',
            9=>'September', 10=>'Oktober', 11=>'November', 12=>'Desember',
        ];
        $tahunList = range(Carbon::now()->year - 2, Carbon::now()->year);

        return view('kepala_sekolah.Laporan', array_merge($laporan, compact(
            'tipe', 'bulan', 'tahun', 'kelas', 'title',
            'kelasList', 'bulanList', 'tahunList'
        )));
    }

    /**
     * Export laporan ke PDF (stub — gunakan dompdf/snappy).
     */
    public function export(Request $request)
    {
        // TODO: Implementasi export PDF menggunakan barryvdh/laravel-dompdf
        // $pdf = PDF::loadView('kepala_sekolah.laporan_pdf', $data);
        // return $pdf->download('laporan_absensi.pdf');

        return back()->with('info', 'Fitur export PDF sedang dalam pengembangan.');
    }

    private function laporanGuru(int $bulan, int $tahun): array
    {
        $guruList = Guru::with(['absenGuru' => function ($q) use ($bulan, $tahun) {
            $q->whereMonth('tanggal', $bulan)
              ->whereYear('tanggal', $tahun)
              ->orderBy('tanggal');
        }])->orderBy('nama')->get();

        $totalHadir = 0;
        $totalAlpha = 0;

        $rows = $guruList->map(function ($guru) use (&$totalHadir, &$totalAlpha) {
            $hadir = $guru->absenGuru->where('status', 'hadir')->count();
            $alpha = $guru->absenGuru->where('status', 'alpha')->count();
            $totalHadir += $hadir;
            $totalAlpha += $alpha;
            return [
                'nama'       => $guru->nama,
                'nip'        => $guru->nip ?? '-',
                'hadir'      => $hadir,
                'sakit'      => $guru->absenGuru->where('status', 'sakit')->count(),
                'izin'       => $guru->absenGuru->where('status', 'izin')->count(),
                'alpha'      => $alpha,
                'total'      => $guru->absenGuru->count(),
                'detail'     => $guru->absenGuru,
            ];
        });

        return compact('rows', 'totalHadir', 'totalAlpha');
    }

    private function laporanSiswa(int $bulan, int $tahun, string $kelas = ''): array
    {
        $query = Siswa::with(['absenSiswa' => function ($q) use ($bulan, $tahun) {
            $q->whereMonth('tanggal', $bulan)
              ->whereYear('tanggal', $tahun)
              ->orderBy('tanggal');
        }])->orderBy('kelas')->orderBy('nama');

        if ($kelas) {
            $query->where('kelas', $kelas);
        }

        $siswaList = $query->get();
        $totalHadir = 0;
        $totalAlpha = 0;

        $rows = $siswaList->map(function ($siswa) use (&$totalHadir, &$totalAlpha) {
            $hadir = $siswa->absenSiswa->where('status', 'hadir')->count();
            $alpha = $siswa->absenSiswa->where('status', 'alpha')->count();
            $totalHadir += $hadir;
            $totalAlpha += $alpha;
            return [
                'nama'    => $siswa->nama,
                'nis'     => $siswa->nis ?? '-',
                'kelas'   => $siswa->kelas,
                'hadir'   => $hadir,
                'sakit'   => $siswa->absenSiswa->where('status', 'sakit')->count(),
                'izin'    => $siswa->absenSiswa->where('status', 'izin')->count(),
                'alpha'   => $alpha,
                'total'   => $siswa->absenSiswa->count(),
                'detail'  => $siswa->absenSiswa,
            ];
        });

        return compact('rows', 'totalHadir', 'totalAlpha');
    }
}