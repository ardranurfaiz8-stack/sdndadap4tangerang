@extends('layouts.app')

@section('title', 'Data Siswa')

@section('content')

{{-- Stat Cards --}}
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon green"><span style="font-size:24px;">🎓</span></div>
        <div><div class="stat-value">{{ $totalSiswa }}</div><div class="stat-label">Total Siswa</div></div>
    </div>
    <div class="stat-card">
        <div class="stat-icon blue"><span style="font-size:24px;">👦</span></div>
        <div><div class="stat-value">{{ $siswaLaki }}</div><div class="stat-label">Laki-laki</div></div>
    </div>
    <div class="stat-card">
        <div class="stat-icon amber"><span style="font-size:24px;">👧</span></div>
        <div><div class="stat-value">{{ $siswaPerempuan }}</div><div class="stat-label">Perempuan</div></div>
    </div>
    <div class="stat-card">
        <div class="stat-icon red"><span style="font-size:24px;">🏫</span></div>
        <div><div class="stat-value">{{ $jumlahKelas }}</div><div class="stat-label">Jumlah Kelas</div></div>
    </div>
</div>

{{-- Tabel --}}
<div class="card" style="margin-top:1.25rem;">
    <div class="card-header" style="flex-wrap:wrap;gap:.75rem;">
        <span class="card-title">📋 Daftar Siswa</span>
        <form method="GET" action="{{ route('kepala-sekolah.siswa.index') }}" style="display:flex;gap:.5rem;align-items:center;flex-wrap:wrap;">
            <select name="kelas" class="form-control" onchange="this.form.submit()" style="width:130px;">
                <option value="">Semua Kelas</option>
                @foreach($kelasList as $k)
                <option value="{{ $k }}" {{ request('kelas')===$k?'selected':'' }}>Kelas {{ $k }}</option>
                @endforeach
            </select>
            <input type="text" name="search" class="form-control" placeholder="Cari nama / NIS..." value="{{ request('search') }}" style="width:180px;">
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
                        <th>NIS</th>
                        <th>Kelas</th>
                        <th>L/P</th>
                        <th>Orang Tua</th>
                        <th>No. Telp</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($siswa as $i => $s)
                    <tr>
                        <td>{{ $siswa->firstItem() + $i }}</td>
                        <td>
                            <div style="display:flex;align-items:center;gap:10px;">
                                <div style="width:32px;height:32px;border-radius:50%;background:linear-gradient(135deg,#3498db,#2980b9);display:flex;align-items:center;justify-content:center;color:white;font-size:11px;font-weight:800;flex-shrink:0;">
                                    {{ strtoupper(substr($s->nama,0,2)) }}
                                </div>
                                <div>
                                    <div style="font-weight:700;">{{ $s->nama }}</div>
                                    <div class="text-muted" style="font-size:12px;">{{ $s->tempat_lahir ?? '' }}{{ $s->tanggal_lahir ? ', '.$s->tanggal_lahir->format('d/m/Y') : '' }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="text-muted" style="font-family:monospace;font-size:12px;">{{ $s->nis }}</td>
                        <td><span class="badge" style="background:#E3F2FD;color:#1565C0;">{{ $s->kelas }}</span></td>
                        <td>
                            <span class="badge" style="background:{{ $s->jenis_kelamin === 'Laki-laki' ? '#E3F2FD' : '#FFF8E1' }};color:{{ $s->jenis_kelamin === 'Laki-laki' ? '#1565C0' : '#F57F17' }};">
                                {{ $s->jenis_kelamin === 'Laki-laki' ? 'L' : 'P' }}
                            </span>
                        </td>
                        <td class="text-muted">{{ $s->nama_orang_tua ?? '-' }}</td>
                        <td class="text-muted">{{ $s->no_telp ?? '-' }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" style="text-align:center;padding:2.5rem;color:var(--gray-500);">
                            Belum ada data siswa.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($siswa->hasPages())
    <div style="padding:1rem 1.25rem;">{{ $siswa->withQueryString()->links() }}</div>
    @endif
</div>

@endsection
