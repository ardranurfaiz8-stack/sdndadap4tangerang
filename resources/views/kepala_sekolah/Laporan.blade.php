@extends('layouts.app')

@section('title', $title)

@section('content')
<div class="page-header">
    <h1 class="page-title">{{ $title }}</h1>
    <p class="page-subtitle">Data lengkap per {{ $tipe === 'guru' ? 'guru' : 'siswa' }} beserta detail harian</p>
</div>

{{-- Filter --}}
<div class="card" style="margin-bottom: 24px;">
    <div class="card-body">
        <form method="GET" action="{{ route('kepala-sekolah.laporan.index') }}" style="display: flex; gap: 16px; flex-wrap: wrap; align-items: flex-end;">
            <div class="form-group" style="margin-bottom: 0; flex: 1; min-width: 140px;">
                <label class="form-label">Tipe</label>
                <select name="tipe" class="form-control" onchange="this.form.submit()">
                    <option value="guru"  {{ $tipe === 'guru'  ? 'selected' : '' }}>Guru</option>
                    <option value="siswa" {{ $tipe === 'siswa' ? 'selected' : '' }}>Siswa</option>
                </select>
            </div>
            <div class="form-group" style="margin-bottom: 0; flex: 1; min-width: 140px;">
                <label class="form-label">Bulan</label>
                <select name="bulan" class="form-control">
                    @foreach($bulanList as $num => $nama)
                        <option value="{{ $num }}" {{ $bulan == $num ? 'selected' : '' }}>{{ $nama }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group" style="margin-bottom: 0; flex: 1; min-width: 120px;">
                <label class="form-label">Tahun</label>
                <select name="tahun" class="form-control">
                    @foreach($tahunList as $y)
                        <option value="{{ $y }}" {{ $tahun == $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endforeach
                </select>
            </div>
            @if($tipe === 'siswa')
            <div class="form-group" style="margin-bottom: 0; flex: 1; min-width: 120px;">
                <label class="form-label">Kelas</label>
                <select name="kelas" class="form-control">
                    <option value="">Semua Kelas</option>
                    @foreach($kelasList as $k)
                        <option value="{{ $k }}" {{ $kelas === $k ? 'selected' : '' }}>{{ $k }}</option>
                    @endforeach
                </select>
            </div>
            @endif
            <div style="display: flex; gap: 8px; margin-bottom: 0;">
                <button type="submit" class="btn btn-primary">Tampilkan</button>
                <a href="{{ route('kepala-sekolah.laporan.export', request()->query()) }}" class="btn btn-secondary">
                    Export PDF
                </a>
            </div>
        </form>
    </div>
</div>

{{-- Ringkasan Total --}}
<div class="card" style="margin-bottom: 24px;">
    <div class="card-header">
        <h3 class="card-title">Ringkasan — {{ $bulanList[$bulan] ?? '' }} {{ $tahun }}</h3>
    </div>
    <div class="card-body">
        <div style="display: flex; gap: 32px; flex-wrap: wrap;">
            <div style="text-align: center;">
                <div style="font-size: 28px; font-weight: 700; color: #2ecc71;">{{ $totalHadir }}</div>
                <div class="text-muted" style="font-size: 13px;">Total Hadir</div>
            </div>
            <div style="text-align: center;">
                <div style="font-size: 28px; font-weight: 700; color: #e74c3c;">{{ $totalAlpha }}</div>
                <div class="text-muted" style="font-size: 13px;">Total Alpha</div>
            </div>
            <div style="text-align: center;">
                <div style="font-size: 28px; font-weight: 700; color: #333;">{{ $rows->count() }}</div>
                <div class="text-muted" style="font-size: 13px;">Total {{ $tipe === 'guru' ? 'Guru' : 'Siswa' }}</div>
            </div>
        </div>
    </div>
</div>

{{-- Tabel Laporan Lengkap --}}
<div class="card">
    <div class="card-header"><h3 class="card-title">Detail Laporan</h3></div>
    <div class="card-body" style="padding: 0;">
        <div class="table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        @if($tipe === 'guru')
                            <th>NIP</th>
                        @else
                            <th>NIS</th>
                            <th>Kelas</th>
                        @endif
                        <th>Hadir</th>
                        <th>Sakit</th>
                        <th>Izin</th>
                        <th>Alpha</th>
                        <th>Total</th>
                        <th>% Hadir</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($rows as $i => $row)
                        @php $persen = $row['total'] > 0 ? round(($row['hadir'] / $row['total']) * 100) : 0; @endphp
                        <tr>
                            <td>{{ $i + 1 }}</td>
                            <td><strong>{{ $row['nama'] }}</strong></td>
                            @if($tipe === 'guru')
                                <td class="text-muted">{{ $row['nip'] }}</td>
                            @else
                                <td class="text-muted">{{ $row['nis'] }}</td>
                                <td>{{ $row['kelas'] }}</td>
                            @endif
                            <td><span style="color: #2ecc71; font-weight: 600;">{{ $row['hadir'] }}</span></td>
                            <td><span style="color: #f39c12; font-weight: 600;">{{ $row['sakit'] }}</span></td>
                            <td><span style="color: #3498db; font-weight: 600;">{{ $row['izin'] }}</span></td>
                            <td><span style="color: #e74c3c; font-weight: 600;">{{ $row['alpha'] }}</span></td>
                            <td>{{ $row['total'] }}</td>
                            <td>
                                <span style="font-weight: 700; color: {{ $persen >= 80 ? '#2ecc71' : ($persen >= 60 ? '#f39c12' : '#e74c3c') }};">
                                    {{ $persen }}%
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ $tipe === 'guru' ? 9 : 10 }}" class="text-center text-muted" style="padding: 40px;">
                                Tidak ada data untuk periode ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection