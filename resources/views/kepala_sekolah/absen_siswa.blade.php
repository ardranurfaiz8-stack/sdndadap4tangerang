@extends('layouts.app')

@section('title', 'Absensi Siswa')

@section('content')

{{-- Stat Cards --}}
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon green"><span style="font-size:24px;">✅</span></div>
        <div><div class="stat-value">{{ $hadirHariIni }}</div><div class="stat-label">Hadir</div></div>
    </div>
    <div class="stat-card">
        <div class="stat-icon amber"><span style="font-size:24px;">📄</span></div>
        <div><div class="stat-value">{{ $izinHariIni }}</div><div class="stat-label">Izin</div></div>
    </div>
    <div class="stat-card">
        <div class="stat-icon blue"><span style="font-size:24px;">🏥</span></div>
        <div><div class="stat-value">{{ $sakitHariIni }}</div><div class="stat-label">Sakit</div></div>
    </div>
    <div class="stat-card">
        <div class="stat-icon red"><span style="font-size:24px;">❌</span></div>
        <div><div class="stat-value">{{ $alphaHariIni }}</div><div class="stat-label">Alpha</div></div>
    </div>
</div>

{{-- Filter --}}
<div class="card" style="margin-top:1.25rem;">
    <div class="card-header" style="flex-wrap:wrap;gap:.75rem;">
        <span class="card-title">📋 Data Absensi Siswa</span>
        <form method="GET" action="{{ route('kepala-sekolah.absen-siswa.index') }}" style="display:flex;gap:.5rem;align-items:center;flex-wrap:wrap;">
            <input type="date" name="tanggal" class="form-control" value="{{ $today }}" onchange="this.form.submit()" style="width:170px;">
            <select name="kelas" class="form-control" onchange="this.form.submit()" style="width:130px;">
                <option value="">Semua Kelas</option>
                @foreach($kelasList as $k)
                <option value="{{ $k }}" {{ $kelas===$k?'selected':'' }}>Kelas {{ $k }}</option>
                @endforeach
            </select>
            <input type="text" name="search" class="form-control" placeholder="Cari nama siswa..." value="{{ request('search') }}" style="width:180px;">
            <button type="submit" class="btn btn-primary" style="padding:.55rem 1.2rem;">🔍 Cari</button>
        </form>
    </div>
    <div class="card-body" style="padding:0;">
        <div class="table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nama Siswa</th>
                        <th>NIS</th>
                        <th>Kelas</th>
                        <th>Tanggal</th>
                        <th>Status</th>
                        <th>Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($absensi as $i => $a)
                    <tr>
                        <td>{{ $absensi->firstItem() + $i }}</td>
                        <td><strong>{{ $a->siswa->nama ?? '-' }}</strong></td>
                        <td class="text-muted" style="font-family:monospace;font-size:12px;">{{ $a->siswa->nis ?? '-' }}</td>
                        <td><span class="badge" style="background:#E3F2FD;color:#1565C0;">{{ $a->siswa->kelas ?? '-' }}</span></td>
                        <td>{{ \Carbon\Carbon::parse($a->tanggal)->format('d/m/Y') }}</td>
                        <td>
                            @php $st = strtolower($a->status); @endphp
                            <span class="badge badge-{{ $st }}">{{ ucfirst($st) }}</span>
                        </td>
                        <td class="text-muted">{{ $a->keterangan ?? '-' }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" style="text-align:center;padding:2.5rem;color:var(--gray-500);">
                            Belum ada data absensi siswa untuk tanggal ini.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($absensi->hasPages())
    <div style="padding:1rem 1.25rem;">{{ $absensi->withQueryString()->links() }}</div>
    @endif
</div>

@endsection
