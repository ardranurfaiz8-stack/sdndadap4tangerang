@extends('layouts.app')
@section('title','Profil Guru')

@push('styles')
<style>
    .stat-card{background:white;border-radius:16px;padding:1.25rem;box-shadow:var(--shadow-sm);border:1px solid var(--gray-200);display:flex;align-items:center;gap:1rem}
    .card-hd{padding:1rem 1.25rem;border-bottom:1px solid var(--gray-100);display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:.5rem}
    .tw{overflow-x:auto}
    .g2{display:grid;grid-template-columns:1fr 1fr;gap:1rem}
    .fg{margin-bottom:.75rem}
    .fl{display:block;font-size:.78rem;font-weight:600;color:var(--gray-700);margin-bottom:4px}
    .fc{width:100%;padding:.55rem .75rem;border:1.5px solid var(--gray-200);border-radius:8px;font-family:inherit;font-size:.83rem;color:var(--gray-900);outline:none;background:#fff;transition:border-color .2s}
    .fw7{font-weight:700}
    .tm{font-size:.82rem;color:var(--gray-500)}
    .flex{display:flex}
    .gap2{gap:.5rem}
    .stat-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:.75rem;margin-bottom:1.25rem}
    .si{width:44px;height:44px;border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:20px;flex-shrink:0}
    .si-g{background:#E8F5E9}.si-y{background:#FFF8E1}.si-b{background:#E3F2FD}.si-r{background:var(--red-pale)}
    .sv{font-size:1.5rem;font-weight:800;color:var(--gray-900);line-height:1}
    .sl{font-size:.72rem;color:var(--gray-500);margin-top:2px}
    .av{width:32px;height:32px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:11px;font-weight:800;flex-shrink:0}
    .badge.bg-g{background:#E8F5E9;color:#2E7D32}
    .badge.bg-y{background:#FFF8E1;color:#F57F17}
    .badge.bg-b{background:#E3F2FD;color:#1565C0}
    .badge.bg-r{background:var(--red-pale);color:var(--red)}
    .btn-r{background:linear-gradient(135deg,var(--red),var(--red-light));color:#fff}
    .btn-r:hover{transform:translateY(-1px);box-shadow:0 4px 12px rgba(192,57,43,.35);color:#fff}
    .btn-s{background:var(--gray-100);color:var(--gray-700)}
    .btn-sm{padding:.4rem .7rem;font-size:.78rem}
    .empty{text-align:center;padding:2rem;color:var(--gray-400)}
    .ei{font-size:2.5rem;margin-bottom:.5rem}
    @media(max-width:768px){.stat-grid{grid-template-columns:repeat(2,1fr)}}
    @media print{.no-print{display:none!important}}
</style>
@endpush

@section('content')

<div class="stat-grid">
    <div class="stat-card">
        <div class="si si-g">👨‍🏫</div>
        <div><div class="sv">{{ $totalGuru }}</div><div class="sl">Total Guru</div></div>
    </div>
    <div class="stat-card">
        <div class="si si-b">👨</div>
        <div><div class="sv">{{ $guruLaki }}</div><div class="sl">Laki-laki</div></div>
    </div>
    <div class="stat-card">
        <div class="si si-y">👩</div>
        <div><div class="sv">{{ $guruPerempuan }}</div><div class="sl">Perempuan</div></div>
    </div>
    <div class="stat-card">
        <div class="si si-r">📚</div>
        <div><div class="sv">{{ $guruAktif }}</div><div class="sl">Guru Aktif</div></div>
    </div>
</div>

<div class="card">
    <div class="card-hd">
        <div class="card-title">📋 Daftar Guru</div>
        <div class="flex gap2">
            <form method="GET" action="{{ route('guru.profil_guru.index') }}" class="flex gap2">
                <input type="text" name="search" class="fc" placeholder="Cari nama / NIP..."
                       value="{{ request('search') }}" style="width:200px;">
                <button type="submit" class="btn btn-s btn-sm">🔍 Cari</button>
            </form>
            <a href="{{ route('guru.profil_guru.create') }}" class="btn btn-r btn-sm">+ Tambah Guru</a>
        </div>
    </div>

    <div class="tw">
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nama</th>
                    <th>NIP</th>
                    <th>L/P</th>
                    <th>Jabatan</th>
                    <th>No. Telp</th>
                    <th class="no-print">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($guru as $i => $g)
                <tr>
                    <td class="tm">{{ $guru->firstItem() + $i }}</td>
                    <td>
                        <div class="flex gap2" style="align-items:center;">
                            <div class="av" style="background:linear-gradient(135deg,var(--red),var(--red-light));color:#fff;">
                                {{ strtoupper(substr($g->nama, 0, 2)) }}
                            </div>
                            <div>
                                <div class="fw7">{{ $g->nama }}</div>
                                <div class="tm">{{ $g->email ?? '-' }}</div>
                            </div>
                        </div>
                    </td>
                    <td style="font-family:monospace;font-size:12px;">{{ $g->nip ?? '-' }}</td>
                    <td>
                        <span class="badge {{ $g->jenis_kelamin === 'Laki-laki' ? 'bg-b' : 'bg-y' }}">
                            {{ $g->jenis_kelamin === 'Laki-laki' ? 'L' : 'P' }}
                        </span>
                    </td>
                    <td class="tm">{{ $g->jabatan ?? '-' }}</td>
                    <td class="tm">{{ $g->no_hp ?? $g->no_telp ?? '-' }}</td>
                    <td class="no-print">
                        <div class="flex gap2">
                            <a href="{{ route('guru.profil_guru.edit', $g->id) }}"
                               class="btn btn-s btn-sm">✏️ Edit</a>
                            <form method="POST"
                                action="{{ route('guru.profil_guru.destroy', $g->id) }}"
                                onsubmit="return confirm('Yakin hapus data guru ini?')"
                                style="display:inline;">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm"
                                    style="background:#FFEBEE;color:#c0392b;border:none;">🗑️</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7">
                        <div class="empty">
                            <div class="ei">👨‍🏫</div>
                            <p>Belum ada data guru.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($guru->hasPages())
    <div style="padding:1rem 1.25rem;">{{ $guru->withQueryString()->links() }}</div>
    @endif
</div>

@endsection