<?php

namespace App\Http\Controllers\kepala_sekolah;

use App\Http\Controllers\Controller;
use App\Models\AbsenGuru;
use App\Models\AbsenSiswa;
use App\Models\Guru;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Carbon\Carbon;

class RekapController extends Controller
{
    public function index(Request $request)
    {
        $tipe  = $request->get('tipe', 'siswa');
        $bulan = $request->get('bulan', Carbon::now()->month);
        $tahun = $request->get('tahun', Carbon::now()->year);
        $kelas = $request->get('kelas', '');

        if ($tipe === 'guru') {
            $data = $this->rekapGuru($bulan, $tahun);
        } else {
            $data = $this->rekapSiswa($bulan, $tahun, $kelas);
        }

        $rekapDetail = $data['rekapDetail'];
        $terendah    = $data['terendah'];
        $stats       = $data['stats'];

        $totalAll = max($stats['hadir'] + $stats['sakit'] + $stats['izin'] + $stats['alpha'], 1);
        $rataHadir = round(($stats['hadir'] / $totalAll) * 100);
        $rataSakit = round(($stats['sakit'] / $totalAll) * 100);
        $rataIzin  = round(($stats['izin']  / $totalAll) * 100);
        $rataAlpha = round(($stats['alpha'] / $totalAll) * 100);

        $trendBulanan = [];
        for ($i = 5; $i >= 0; $i--) {
            $dt = Carbon::now()->subMonths($i);
            if ($tipe === 'guru') {
                $total = AbsenGuru::whereMonth('tanggal', $dt->month)->whereYear('tanggal', $dt->year)->count();
                $hadir = AbsenGuru::whereMonth('tanggal', $dt->month)->whereYear('tanggal', $dt->year)->where('status', 'Hadir')->count();
            } else {
                $q = AbsenSiswa::whereMonth('tanggal', $dt->month)->whereYear('tanggal', $dt->year);
                if ($kelas) $q->whereHas('siswa', fn($sq) => $sq->where('kelas', $kelas));
                $total = (clone $q)->count();
                $hadir = (clone $q)->where('status', 'Hadir')->count();
            }
            $trendBulanan[] = [
                'bulan' => $dt->translatedFormat('M Y'),
                'pct'   => $total > 0 ? round(($hadir / $total) * 100) : 0,
            ];
        }

        $kelasList = Siswa::select('kelas')->distinct()->orderBy('kelas')->pluck('kelas');
        $bulanList = [
            1=>'Januari',2=>'Februari',3=>'Maret',4=>'April',
            5=>'Mei',6=>'Juni',7=>'Juli',8=>'Agustus',
            9=>'September',10=>'Oktober',11=>'November',12=>'Desember',
        ];
        $tahunList = range(Carbon::now()->year - 2, Carbon::now()->year);

        return view('kepala_sekolah.Rekap', compact(
            'tipe','bulan','tahun','kelas',
            'rekapDetail','terendah','trendBulanan',
            'rataHadir','rataSakit','rataIzin','rataAlpha',
            'kelasList','bulanList','tahunList'
        ));
    }

    private function rekapGuru(int $bulan, int $tahun): array
    {
        $guruList = Guru::with(['absenGuru' => fn($q) =>
            $q->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)
        ])->orderBy('nama')->get();

        $rekapDetail = $guruList->map(function ($guru) {
            $absen = $guru->absenGuru;
            $total = $absen->count();
            $hadir = $absen->whereIn('status', ['Hadir','hadir'])->count();
            return [
                'nama'           => $guru->nama,
                'kelas_jabatan'  => $guru->mata_pelajaran ?? '-',
                'hadir'          => $hadir,
                'sakit'          => $absen->whereIn('status', ['Sakit','sakit'])->count(),
                'izin'           => $absen->whereIn('status', ['Izin','izin'])->count(),
                'alpha'          => $absen->whereIn('status', ['Alpha','alpha'])->count(),
                'pct_hadir'      => $total > 0 ? round(($hadir / $total) * 100) : 0,
            ];
        })->values();

        $stats = [
            'hadir' => $rekapDetail->sum('hadir'),
            'sakit' => $rekapDetail->sum('sakit'),
            'izin'  => $rekapDetail->sum('izin'),
            'alpha' => $rekapDetail->sum('alpha'),
        ];

        $terendah = $rekapDetail->sortBy('pct_hadir')->take(5)->values();

        return compact('rekapDetail', 'stats', 'terendah');
    }

    private function rekapSiswa(int $bulan, int $tahun, string $kelas = ''): array
    {
        $query = Siswa::with(['absenSiswa' => fn($q) =>
            $q->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)
        ])->orderBy('kelas')->orderBy('nama');

        if ($kelas) $query->where('kelas', $kelas);

        $siswaList = $query->get();

        $rekapDetail = $siswaList->map(function ($siswa) {
            $absen = $siswa->absenSiswa;
            $total = $absen->count();
            $hadir = $absen->whereIn('status', ['Hadir','hadir'])->count();
            return [
                'nama'           => $siswa->nama,
                'kelas_jabatan'  => $siswa->kelas,
                'hadir'          => $hadir,
                'sakit'          => $absen->whereIn('status', ['Sakit','sakit'])->count(),
                'izin'           => $absen->whereIn('status', ['Izin','izin'])->count(),
                'alpha'          => $absen->whereIn('status', ['Alpha','alpha'])->count(),
                'pct_hadir'      => $total > 0 ? round(($hadir / $total) * 100) : 0,
            ];
        })->values();

        $stats = [
            'hadir' => $rekapDetail->sum('hadir'),
            'sakit' => $rekapDetail->sum('sakit'),
            'izin'  => $rekapDetail->sum('izin'),
            'alpha' => $rekapDetail->sum('alpha'),
        ];

        $terendah = $rekapDetail->sortBy('pct_hadir')->take(5)->values();

        return compact('rekapDetail', 'stats', 'terendah');
    }
}
