@extends('layouts.app')

@section('title', 'Detail Profil Guru')

@section('content')

<div style="margin-bottom:1.25rem;display:flex;gap:0.75rem;">
    <a href="{{ route('admin.guru.index') }}" class="btn btn-secondary" style="padding:0.5rem 1rem;font-size:0.83rem;">← Kembali</a>
    <a href="{{ route('admin.guru.edit', $guru->id) }}" class="btn btn-primary" style="padding:0.5rem 1rem;font-size:0.83rem;">✏️ Edit Profil</a>
</div>

<div style="display:grid;grid-template-columns:300px 1fr;gap:1.25rem;align-items:start;">

    {{-- KARTU KIRI --}}
    <div class="card">
        <div class="card-body">
            <div class="profile-avatar-area">
                <div class="profile-avatar-big">{{ strtoupper(substr($guru->nama ?? 'G', 0, 2)) }}</div>
                <div class="profile-name">{{ $guru->nama }}</div>
                <div class="profile-sub">Guru {{ $guru->mata_pelajaran ?? '' }}</div>
                <div style="margin-top:0.5rem;"><span class="role-badge role-guru">Guru</span></div>
            </div>
            <div style="font-size:0.78rem;color:var(--gray-700);line-height:2.2;">
                <div>🆔 NIP: <strong>{{ $guru->nip ?? '-' }}</strong></div>
                <div>📚 Mapel: <strong>{{ $guru->mata_pelajaran ?? '-' }}</strong></div>
                <div>👤 L/P: <strong>{{ $guru->jenis_kelamin == 'L' ? 'Laki-laki' : ($guru->jenis_kelamin == 'P' ? 'Perempuan' : '-') }}</strong></div>
                <div>📞 Telp: <strong>{{ $guru->no_telp ?? '-' }}</strong></div>
                <div>📅 Bergabung: <strong>{{ $guru->created_at ? $guru->created_at->format('Y') : '-' }}</strong></div>
            </div>
        </div>
    </div>

    {{-- DETAIL KANAN --}}
    <div class="card">
        <div class="card-header">
            <span class="card-title">📋 Detail Profil Guru</span>
        </div>
        <div class="card-body">

            <div class="form-section">
                <div class="form-section-title">Data Pribadi</div>
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;">
                    @foreach([
                        ['Nama Lengkap', $guru->nama ?? '-'],
                        ['NIP', $guru->nip ?? '-'],
                        ['Jenis Kelamin', $guru->jenis_kelamin == 'L' ? 'Laki-laki' : ($guru->jenis_kelamin == 'P' ? 'Perempuan' : '-')],
                        ['Tanggal Lahir', $guru->tanggal_lahir ? \Carbon\Carbon::parse($guru->tanggal_lahir)->translatedFormat('d F Y') : '-'],
                        ['Tempat Lahir', $guru->tempat_lahir ?? '-'],
                    ] as [$label, $value])
                    <div>
                        <div style="font-size:0.72rem;font-weight:700;text-transform:uppercase;letter-spacing:0.5px;color:var(--gray-500);margin-bottom:4px;">{{ $label }}</div>
                        <div style="font-size:0.88rem;font-weight:600;color:var(--gray-900);">{{ $value }}</div>
                    </div>
                    @endforeach
                </div>
            </div>

            <div class="form-section">
                <div class="form-section-title">Data Akademik</div>
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;">
                    @foreach([
                        ['Mata Pelajaran', $guru->mata_pelajaran ?? '-'],
                        ['Kelas Pengajar', $guru->kelas_pengajar ?? '-'],
                        ['Pendidikan Terakhir', $guru->pendidikan_terakhir ?? '-'],
                        ['Tahun Masuk', $guru->tahun_masuk ?? '-'],
                    ] as [$label, $value])
                    <div>
                        <div style="font-size:0.72rem;font-weight:700;text-transform:uppercase;letter-spacing:0.5px;color:var(--gray-500);margin-bottom:4px;">{{ $label }}</div>
                        <div style="font-size:0.88rem;font-weight:600;color:var(--gray-900);">{{ $value }}</div>
                    </div>
                    @endforeach
                </div>
            </div>

            <div class="form-section">
                <div class="form-section-title">Kontak</div>
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;">
                    <div>
                        <div style="font-size:0.72rem;font-weight:700;text-transform:uppercase;letter-spacing:0.5px;color:var(--gray-500);margin-bottom:4px;">Email</div>
                        <div style="font-size:0.88rem;font-weight:600;color:var(--gray-900);">{{ $guru->email ?? '-' }}</div>
                    </div>
                    <div>
                        <div style="font-size:0.72rem;font-weight:700;text-transform:uppercase;letter-spacing:0.5px;color:var(--gray-500);margin-bottom:4px;">No. Telepon</div>
                        <div style="font-size:0.88rem;font-weight:600;color:var(--gray-900);">{{ $guru->no_telp ?? '-' }}</div>
                    </div>
                    <div style="grid-column:1/-1;">
                        <div style="font-size:0.72rem;font-weight:700;text-transform:uppercase;letter-spacing:0.5px;color:var(--gray-500);margin-bottom:4px;">Alamat</div>
                        <div style="font-size:0.88rem;font-weight:600;color:var(--gray-900);">{{ $guru->alamat ?? '-' }}</div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

@endsection