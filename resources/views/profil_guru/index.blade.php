@extends('layouts.app')

@section('title', 'Profil Guru')

@section('content')

@if(!$selectedGuru)
{{-- KOSONG: belum ada data guru --}}
<div class="card" style="text-align:center;padding:3rem 2rem;">
    <div style="font-size:48px;margin-bottom:1rem;">👨‍🏫</div>
    <div style="font-size:1rem;font-weight:700;color:var(--gray-900);margin-bottom:0.5rem;">Belum ada data guru</div>
    <div style="font-size:0.85rem;color:var(--gray-500);margin-bottom:1.5rem;">Tambahkan data guru terlebih dahulu.</div>
    <a href="{{ route('admin.guru.create') }}" class="btn btn-primary" style="display:inline-flex;margin:0 auto;">
        ➕ Tambah Guru
    </a>
</div>

@else

<div style="display:grid;grid-template-columns:280px 1fr;gap:1.25rem;align-items:start;">

    {{-- KARTU PROFIL KIRI --}}
    <div class="card">
        <div class="card-body">
            <div class="profile-avatar-area">
                <div class="profile-avatar-big">
                    {{ strtoupper(substr($selectedGuru->nama ?? 'G', 0, 2)) }}
                </div>
                <div class="profile-name">{{ $selectedGuru->nama ?? '-' }}</div>
                <div class="profile-sub">Guru {{ $selectedGuru->mata_pelajaran ?? '' }}</div>
                <div style="margin-top:0.5rem;">
                    <span class="role-badge role-guru">Guru</span>
                </div>
            </div>
            <div style="font-size:0.78rem;color:var(--gray-700);line-height:2.2;">
                <div>🆔 NIP: <strong>{{ $selectedGuru->nip ?? '-' }}</strong></div>
                <div>📚 Mapel: <strong>{{ $selectedGuru->mata_pelajaran ?? '-' }}</strong></div>
                <div>📞 Telp: <strong>{{ $selectedGuru->no_telp ?? '-' }}</strong></div>
                <div>🏫 Kelas: <strong>{{ $selectedGuru->kelas_pengajar ?? '-' }}</strong></div>
                <div>📅 Bergabung: <strong>{{ $selectedGuru->created_at ? $selectedGuru->created_at->format('Y') : '-' }}</strong></div>
            </div>
        </div>
    </div>

    {{-- FORM EDIT KANAN --}}
    <div class="card">
        <div class="card-header">
            <span class="card-title">✏️ Edit Profil Guru</span>
            @if($gurus->count() > 1)
            <select onchange="window.location.href='{{ route('admin.profil.index') }}?guru_id='+this.value"
                style="padding:6px 10px;border:1.5px solid var(--gray-200);border-radius:8px;font-family:inherit;font-size:0.8rem;outline:none;">
                @foreach($gurus as $g)
                    <option value="{{ $g->id }}" {{ $selectedGuru->id == $g->id ? 'selected' : '' }}>
                        {{ $g->nama }}
                    </option>
                @endforeach
            </select>
            @endif
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('admin.guru.profil.update', $selectedGuru->id) }}">
                @csrf @method('PUT')

                {{-- DATA PRIBADI --}}
                <div class="form-section">
                    <div class="form-section-title">Data Pribadi</div>
                    <div class="form-row">
                        <div class="form-group">
                            <label>Nama Lengkap</label>
                            <input type="text" name="nama" value="{{ old('nama', $selectedGuru->nama) }}" required>
                        </div>
                        <div class="form-group">
                            <label>NIP</label>
                            <input type="text" name="nip" value="{{ old('nip', $selectedGuru->nip) }}">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label>Jenis Kelamin</label>
                            <select name="jenis_kelamin">
                                <option value="L" {{ old('jenis_kelamin', $selectedGuru->jenis_kelamin) == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                <option value="P" {{ old('jenis_kelamin', $selectedGuru->jenis_kelamin) == 'P' ? 'selected' : '' }}>Perempuan</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Tanggal Lahir</label>
                            @php
                                $tglLahir = $selectedGuru->tanggal_lahir
                                    ? \Carbon\Carbon::parse($selectedGuru->tanggal_lahir)->format('Y-m-d')
                                    : '';
                            @endphp
                            <input type="date" name="tanggal_lahir" value="{{ old('tanggal_lahir', $tglLahir) }}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Tempat Lahir</label>
                        <input type="text" name="tempat_lahir"
                            value="{{ old('tempat_lahir', $selectedGuru->tempat_lahir ?? '') }}"
                            placeholder="Kota tempat lahir...">
                    </div>
                </div>

                {{-- DATA AKADEMIK --}}
                <div class="form-section">
                    <div class="form-section-title">Data Akademik</div>
                    <div class="form-row">
                        <div class="form-group">
                            <label>Mata Pelajaran</label>
                            <input type="text" name="mata_pelajaran"
                                value="{{ old('mata_pelajaran', $selectedGuru->mata_pelajaran ?? '') }}"
                                placeholder="Matematika...">
                        </div>
                        <div class="form-group">
                            <label>Kelas Pengajar</label>
                            <input type="text" name="kelas_pengajar"
                                value="{{ old('kelas_pengajar', $selectedGuru->kelas_pengajar ?? '') }}"
                                placeholder="X IPA 1, XI IPA 1...">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label>Pendidikan Terakhir</label>
                            <select name="pendidikan_terakhir">
                                @foreach(['S1','S2','S3','D3','D4','SMA/SMK'] as $p)
                                    <option value="{{ $p }}"
                                        {{ old('pendidikan_terakhir', $selectedGuru->pendidikan_terakhir ?? '') == $p ? 'selected' : '' }}>
                                        {{ $p }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Tahun Masuk</label>
                            <input type="text" name="tahun_masuk"
                                value="{{ old('tahun_masuk', $selectedGuru->tahun_masuk ?? '') }}"
                                placeholder="2010">
                        </div>
                    </div>
                </div>

                {{-- KONTAK --}}
                <div class="form-section">
                    <div class="form-section-title">Kontak</div>
                    <div class="form-row">
                        <div class="form-group">
                            <label>Email</label>
                            <input type="email" name="email"
                                value="{{ old('email', $selectedGuru->email ?? '') }}"
                                placeholder="email@sekolah.ac.id">
                        </div>
                        <div class="form-group">
                            <label>No. Telepon</label>
                            <input type="text" name="no_telp"
                                value="{{ old('no_telp', $selectedGuru->no_telp ?? '') }}"
                                placeholder="0812-xxxx-xxxx">
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Alamat</label>
                        <textarea name="alamat" rows="3" placeholder="Alamat lengkap...">{{ old('alamat', $selectedGuru->alamat ?? '') }}</textarea>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary" style="width:100%;padding:0.9rem;font-size:0.95rem;">
                    💾 Simpan Perubahan
                </button>
            </form>
        </div>
    </div>
</div>

@endif

@endsection