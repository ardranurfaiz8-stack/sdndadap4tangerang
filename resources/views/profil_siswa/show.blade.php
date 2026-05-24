@extends('layouts.app')

@section('title', 'Detail Profil Siswa')

@section('content')

<div style="margin-bottom:1.25rem;display:flex;gap:0.75rem;">
    <a href="{{ route('admin.siswa.index') }}" class="btn btn-secondary" style="padding:0.5rem 1rem;font-size:0.83rem;">← Kembali</a>
    <a href="{{ route('admin.siswa.edit', $siswa->id) }}" class="btn btn-primary" style="padding:0.5rem 1rem;font-size:0.83rem;">✏️ Edit</a>
</div>

<div style="display:grid;grid-template-columns:300px 1fr;gap:1.25rem;align-items:start;">

    {{-- KARTU KIRI --}}
    <div class="card">
        <div class="card-body">
            <div class="profile-avatar-area">
                <div class="profile-avatar-big">{{ strtoupper(substr($siswa->nama ?? 'S', 0, 2)) }}</div>
                <div class="profile-name">{{ $siswa->nama }}</div>
                <div class="profile-sub">{{ $siswa->kelas ?? '-' }} · {{ $siswa->tahun_masuk ?? date('Y') }}/{{ ($siswa->tahun_masuk ?? date('Y')) + 1 }}</div>
                <div style="margin-top:0.5rem;">
                    <span class="badge" style="background:#E8F5E9;color:#2E7D32;font-size:0.7rem;font-weight:700;padding:3px 12px;border-radius:20px;text-transform:uppercase;letter-spacing:0.5px;">Siswa</span>
                </div>
            </div>
            <div style="font-size:0.78rem;color:var(--gray-700);line-height:2.4;">
                <div>🆔 NIS: <strong>{{ $siswa->nis ?? '-' }}</strong></div>
                <div>🏫 Kelas: <strong>{{ $siswa->kelas ?? '-' }}</strong></div>
                @if($siswa->tanggal_lahir)
                <div>📅 Lahir: <strong>{{ \Carbon\Carbon::parse($siswa->tanggal_lahir)->translatedFormat('d F Y') }}</strong></div>
                @endif
                <div>📞 Ortu: <strong>{{ $siswa->no_telp ?? '-' }}</strong></div>
            </div>
        </div>
    </div>

    {{-- DETAIL KANAN --}}
    <div class="card">
        <div class="card-header">
            <span class="card-title">📋 Detail Profil Siswa</span>
        </div>
        <div class="card-body">

            <div class="form-section">
                <div class="form-section-title">Data Pribadi</div>
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;">
                    @foreach([
                        ['Nama Lengkap', $siswa->nama ?? '-'],
                        ['NIS', $siswa->nis ?? '-'],
                        ['Jenis Kelamin', $siswa->jenis_kelamin == 'L' ? 'Laki-laki' : ($siswa->jenis_kelamin == 'P' ? 'Perempuan' : '-')],
                        ['Tanggal Lahir', $siswa->tanggal_lahir ? \Carbon\Carbon::parse($siswa->tanggal_lahir)->translatedFormat('d F Y') : '-'],
                        ['Tempat Lahir', $siswa->tempat_lahir ?? '-'],
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
                        ['Kelas', $siswa->kelas ?? '-'],
                        ['Tahun Masuk', $siswa->tahun_masuk ?? '-'],
                    ] as [$label, $value])
                    <div>
                        <div style="font-size:0.72rem;font-weight:700;text-transform:uppercase;letter-spacing:0.5px;color:var(--gray-500);margin-bottom:4px;">{{ $label }}</div>
                        <div style="font-size:0.88rem;font-weight:600;color:var(--gray-900);">{{ $value }}</div>
                    </div>
                    @endforeach
                </div>
            </div>

            <div class="form-section">
                <div class="form-section-title">Kontak &amp; Orang Tua</div>
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;">
                    @foreach([
                        ['Email', $siswa->email ?? '-'],
                        ['No. Telp Orang Tua', $siswa->no_telp ?? '-'],
                        ['Nama Orang Tua', $siswa->nama_orang_tua ?? '-'],
                    ] as [$label, $value])
                    <div>
                        <div style="font-size:0.72rem;font-weight:700;text-transform:uppercase;letter-spacing:0.5px;color:var(--gray-500);margin-bottom:4px;">{{ $label }}</div>
                        <div style="font-size:0.88rem;font-weight:600;color:var(--gray-900);">{{ $value }}</div>
                    </div>
                    @endforeach
                    <div style="grid-column:1/-1;">
                        <div style="font-size:0.72rem;font-weight:700;text-transform:uppercase;letter-spacing:0.5px;color:var(--gray-500);margin-bottom:4px;">Alamat</div>
                        <div style="font-size:0.88rem;font-weight:600;color:var(--gray-900);">{{ $siswa->alamat ?? '-' }}</div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

@endsection