@extends('layouts.app')

@section('title', 'Detail Absensi Siswa')

@section('content')

<div style="margin-bottom:1.25rem;">
    <a href="{{ route('admin.absen-siswa.index') }}" class="btn btn-secondary" style="padding:0.5rem 1rem;font-size:0.83rem;">
        ← Kembali
    </a>
</div>

<div style="display:grid;grid-template-columns:280px 1fr;gap:1.25rem;align-items:start;">

    {{-- Info Siswa --}}
    <div class="card">
        <div class="card-body">
            <div class="profile-avatar-area">
                <div class="profile-avatar-big">{{ strtoupper(substr($siswa->nama, 0, 2)) }}</div>
                <div class="profile-name">{{ $siswa->nama }}</div>
                <div class="profile-sub">{{ $siswa->kelas ?? '-' }}</div>
                <div style="margin-top:0.5rem;">
                    <span class="badge" style="background:#E8F5E9;color:#2E7D32;">Siswa</span>
                </div>
            </div>
            <div style="font-size:0.78rem;color:var(--gray-700);line-height:2.2;">
                <div>🆔 NIS: <strong>{{ $siswa->nis ?? '-' }}</strong></div>
                <div>🏫 Kelas: <strong>{{ $siswa->kelas ?? '-' }}</strong></div>
                <div>👤 L/P: <strong>{{ $siswa->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</strong></div>
                <div>📞 Ortu: <strong>{{ $siswa->no_telp ?? '-' }}</strong></div>
            </div>
        </div>
    </div>

    {{-- Riwayat Absensi --}}
    <div class="card">
        <div class="card-header">
            <span class="card-title">📋 Riwayat Absensi (30 Hari Terakhir)</span>
            <div style="display:flex;gap:0.5rem;">
                @php
                    $totalHadir = $absensi->where('status','hadir')->count();
                    $totalAlpha = $absensi->where('status','alpha')->count();
                @endphp
                <span class="badge badge-hadir">Hadir: {{ $totalHadir }}</span>
                <span class="badge badge-alpha">Alpha: {{ $totalAlpha }}</span>
            </div>
        </div>
        <div class="card-body" style="padding:0;">
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Tanggal</th>
                            <th>Status</th>
                            <th>Keterangan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($absensi as $i => $a)
                        <tr>
                            <td style="font-weight:700;color:var(--gray-500);">{{ $i + 1 }}</td>
                            <td style="font-weight:600;font-size:0.85rem;">
                                {{ \Carbon\Carbon::parse($a->tanggal)->translatedFormat('d F Y') }}
                            </td>
                            <td>
                                @php
                                    $bc = ['hadir'=>'badge-hadir','sakit'=>'badge-sakit','izin'=>'badge-izin','alpha'=>'badge-alpha'][$a->status] ?? 'badge-hadir';
                                    $bl = ['hadir'=>'Hadir','sakit'=>'Sakit','izin'=>'Izin','alpha'=>'Alpha'][$a->status] ?? ucfirst($a->status);
                                @endphp
                                <span class="badge {{ $bc }}">{{ $bl }}</span>
                            </td>
                            <td style="font-size:0.78rem;color:var(--gray-500);">{{ $a->keterangan ?? '-' }}</td>
                            <td>
                                <a href="{{ route('admin.absen-siswa.edit', $a->id) }}"
                                    class="btn btn-secondary" style="padding:3px 10px;font-size:0.73rem;">✏️ Edit</a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" style="text-align:center;padding:2rem;color:var(--gray-500);">
                                📭 Belum ada riwayat absensi
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection