@extends('layouts.app')

@section('title', 'Data Guru')

@section('content')

{{-- Stat Cards --}}
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon green"><span style="font-size:24px;">👨‍🏫</span></div>
        <div><div class="stat-value">{{ $totalGuru }}</div><div class="stat-label">Total Guru</div></div>
    </div>
    <div class="stat-card">
        <div class="stat-icon blue"><span style="font-size:24px;">👨</span></div>
        <div><div class="stat-value">{{ $guruLaki }}</div><div class="stat-label">Laki-laki</div></div>
    </div>
    <div class="stat-card">
        <div class="stat-icon amber"><span style="font-size:24px;">👩</span></div>
        <div><div class="stat-value">{{ $guruPerempuan }}</div><div class="stat-label">Perempuan</div></div>
    </div>
</div>

{{-- Tabel --}}
<div class="card" style="margin-top:1.25rem;">
    <div class="card-header" style="flex-wrap:wrap;gap:.75rem;">
        <span class="card-title">📋 Daftar Guru</span>
        <form method="GET" action="{{ route('kepala-sekolah.guru.index') }}" style="display:flex;gap:.5rem;align-items:center;">
            <input type="text" name="search" class="form-control" placeholder="Cari nama / NIP..." value="{{ request('search') }}" style="width:200px;">
            <button type="submit" class="btn btn-primary" style="padding:.55rem 1.2rem;">🔍 Cari</button>
        </form>
    </div>
    <div class="card-body" style="padding:0;">
        <div class="table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nama</th>
                        <th>NIP</th>
                        <th>L/P</th>
                        <th>Mata Pelajaran</th>
                        <th>No. Telp</th>
                        <th>Alamat</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($guru as $i => $g)
                    <tr>
                        <td>{{ $guru->firstItem() + $i }}</td>
                        <td>
                            <div style="display:flex;align-items:center;gap:10px;">
                                <div style="width:32px;height:32px;border-radius:50%;background:linear-gradient(135deg,var(--red),var(--red-light));display:flex;align-items:center;justify-content:center;color:white;font-size:11px;font-weight:800;flex-shrink:0;">
                                    {{ strtoupper(substr($g->nama,0,2)) }}
                                </div>
                                <div>
                                    <div style="font-weight:700;">{{ $g->nama }}</div>
                                    <div class="text-muted" style="font-size:12px;">{{ $g->tempat_lahir ?? '' }}{{ $g->tanggal_lahir ? ', '.$g->tanggal_lahir->format('d/m/Y') : '' }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="text-muted" style="font-family:monospace;font-size:12px;">{{ $g->nip ?? '-' }}</td>
                        <td>
                            <span class="badge" style="background:{{ $g->jenis_kelamin === 'Laki-laki' ? '#E3F2FD' : '#FFF8E1' }};color:{{ $g->jenis_kelamin === 'Laki-laki' ? '#1565C0' : '#F57F17' }};">
                                {{ $g->jenis_kelamin === 'Laki-laki' ? 'L' : 'P' }}
                            </span>
                        </td>
                        <td class="text-muted">{{ $g->mata_pelajaran ?? '-' }}</td>
                        <td class="text-muted">{{ $g->no_telp ?? '-' }}</td>
                        <td class="text-muted" style="max-width:200px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">{{ $g->alamat ?? '-' }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" style="text-align:center;padding:2.5rem;color:var(--gray-500);">
                            Belum ada data guru.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($guru->hasPages())
    <div style="padding:1rem 1.25rem;">{{ $guru->withQueryString()->links() }}</div>
    @endif
</div>

@endsection
