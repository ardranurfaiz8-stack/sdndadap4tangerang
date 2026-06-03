@extends('layouts.app')

@section('title', 'Profil Siswa')

@section('content')

@if(!$selectedSiswa)
<div class="card" style="text-align:center;padding:3rem 2rem;">
    <div style="font-size:48px;margin-bottom:1rem;">👨‍🎓</div>
    <div style="font-size:1rem;font-weight:700;color:var(--gray-900);margin-bottom:0.5rem;">Belum ada data siswa</div>
    <div style="font-size:0.85rem;color:var(--gray-500);margin-bottom:1.5rem;">Tambahkan data siswa terlebih dahulu.</div>
    <a href="{{ route('admin.siswa.create') }}" class="btn btn-primary" style="display:inline-flex;margin:0 auto;">
        ➕ Tambah Siswa
    </a>
</div>

@else

<div style="display:grid;grid-template-columns:280px 1fr;gap:1.25rem;align-items:start;">

    {{-- KARTU PROFIL KIRI --}}
    <div class="card">
        <div class="card-body">
            <div class="profile-avatar-area">
                <div class="profile-avatar-big">
                    {{ strtoupper(substr($selectedSiswa->nama ?? 'S', 0, 2)) }}
                </div>
                <div class="profile-name">{{ $selectedSiswa->nama ?? '-' }}</div>
                <div class="profile-sub">
                    {{ $selectedSiswa->kelas ?? '-' }} · {{ $selectedSiswa->tahun_masuk ?? date('Y') }}/{{ ($selectedSiswa->tahun_masuk ?? date('Y')) + 1 }}
                </div>
                <div style="margin-top:0.5rem;">
                    <span class="badge" style="background:#E8F5E9;color:#2E7D32;font-size:0.7rem;font-weight:700;padding:3px 12px;border-radius:20px;text-transform:uppercase;letter-spacing:0.5px;">Siswa</span>
                </div>
            </div>
            <div style="font-size:0.78rem;color:var(--gray-700);line-height:2.4;">
                <div>🆔 NIS: <strong>{{ $selectedSiswa->nis ?? '-' }}</strong></div>
                <div>🏫 Kelas: <strong>{{ $selectedSiswa->kelas ?? '-' }}</strong></div>
                @if($selectedSiswa->tanggal_lahir)
                <div>📅 Lahir: <strong>{{ \Carbon\Carbon::parse($selectedSiswa->tanggal_lahir)->translatedFormat('d F Y') }}</strong></div>
                @endif
                <div>📞 Ortu: <strong>{{ $selectedSiswa->no_telp ?? '-' }}</strong></div>
            </div>
        </div>
    </div>

    {{-- FORM EDIT KANAN --}}
    <div class="card">
        <div class="card-header">
            <span class="card-title">✏️ Edit Profil Siswa</span>
            <div style="display:flex;gap:0.5rem;align-items:center;flex-wrap:wrap;">
                @if($siswas->count() > 1)
                <select onchange="window.location.href='{{ route('admin.siswa.index') }}?siswa_id='+this.value"
                    style="padding:6px 10px;border:1.5px solid var(--gray-200);border-radius:8px;font-family:inherit;font-size:0.8rem;outline:none;">
                    @foreach($siswas as $s)
                        <option value="{{ $s->id }}" {{ $selectedSiswa->id == $s->id ? 'selected' : '' }}>
                            {{ $s->nama }}
                        </option>
                    @endforeach
                </select>
                @endif
                {{-- Tombol Hapus Siswa --}}
                <button type="button"
                    onclick="openModalHapusSiswa({{ $selectedSiswa->id }}, '{{ addslashes($selectedSiswa->nama) }}')"
                    style="padding:6px 14px;background:#FFEBEE;color:#c0392b;border:1.5px solid #FFCDD2;border-radius:8px;font-size:0.8rem;font-weight:600;cursor:pointer;">
                    🗑️ Hapus Siswa
                </button>
            </div>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('admin.siswa.update', $selectedSiswa->id) }}">
                @csrf @method('PUT')

                {{-- DATA PRIBADI --}}
                <div class="form-section">
                    <div class="form-section-title">Data Pribadi</div>
                    <div class="form-row">
                        <div class="form-group">
                            <label>Nama Lengkap</label>
                            <input type="text" name="nama" value="{{ old('nama', $selectedSiswa->nama) }}" required>
                        </div>
                        <div class="form-group">
                            <label>NIS</label>
                            <input type="text" name="nis" value="{{ old('nis', $selectedSiswa->nis ?? '') }}">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label>Jenis Kelamin</label>
                            <select name="jenis_kelamin">
                                <option value="L" {{ old('jenis_kelamin', $selectedSiswa->jenis_kelamin) == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                <option value="P" {{ old('jenis_kelamin', $selectedSiswa->jenis_kelamin) == 'P' ? 'selected' : '' }}>Perempuan</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Tanggal Lahir</label>
                            @php
                                $tgl = $selectedSiswa->tanggal_lahir
                                    ? \Carbon\Carbon::parse($selectedSiswa->tanggal_lahir)->format('Y-m-d')
                                    : '';
                            @endphp
                            <input type="date" name="tanggal_lahir" value="{{ old('tanggal_lahir', $tgl) }}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Tempat Lahir</label>
                        <input type="text" name="tempat_lahir" value="{{ old('tempat_lahir', $selectedSiswa->tempat_lahir ?? '') }}" placeholder="Jakarta...">
                    </div>
                </div>

                {{-- DATA AKADEMIK --}}
                <div class="form-section">
                    <div class="form-section-title">Data Akademik</div>
                    <div class="form-row">
                        <div class="form-group">
                            <label>Kelas</label>
                            <select name="kelas">
                                <option value="">-- Pilih Kelas --</option>
                                @foreach(['1A','1B','2A','2B','3A','3B','4A','4B','5A','5B','6A','6B'] as $k)
                                    <option value="{{ $k }}" {{ old('kelas', $selectedSiswa->kelas ?? '') == $k ? 'selected' : '' }}>{{ $k }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Tahun Masuk</label>
                            <input type="text" name="tahun_masuk" value="{{ old('tahun_masuk', $selectedSiswa->tahun_masuk ?? '') }}" placeholder="{{ date('Y') }}">
                        </div>
                    </div>
                </div>

                {{-- KONTAK & ORANG TUA --}}
                <div class="form-section">
                    <div class="form-section-title">Kontak &amp; Orang Tua</div>
                    <div class="form-row">
                        <div class="form-group">
                            <label>Email</label>
                            <input type="email" name="email" value="{{ old('email', $selectedSiswa->email ?? '') }}" placeholder="email@student.ac.id">
                        </div>
                        <div class="form-group">
                            <label>No. Telp Orang Tua</label>
                            <input type="text" name="no_telp" value="{{ old('no_telp', $selectedSiswa->no_telp ?? '') }}" placeholder="0821-xxxx-xxxx">
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Nama Orang Tua</label>
                        <input type="text" name="nama_orang_tua" value="{{ old('nama_orang_tua', $selectedSiswa->nama_orang_tua ?? '') }}" placeholder="Nama orang tua...">
                    </div>
                    <div class="form-group">
                        <label>Alamat</label>
                        <textarea name="alamat" rows="3" placeholder="Alamat lengkap...">{{ old('alamat', $selectedSiswa->alamat ?? '') }}</textarea>
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

{{-- MODAL KONFIRMASI HAPUS SISWA --}}
<div id="modalHapusSiswa" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.45);z-index:999;align-items:center;justify-content:center;">
    <div style="background:white;border-radius:16px;padding:2rem;width:100%;max-width:380px;margin:1rem;box-shadow:0 20px 60px rgba(0,0,0,0.2);text-align:center;">
        <div style="font-size:48px;margin-bottom:0.75rem;">🗑️</div>
        <h3 style="font-size:1rem;font-weight:800;color:#1a1a2e;margin-bottom:0.5rem;">Hapus Data Siswa?</h3>
        <p style="font-size:0.83rem;color:#666;margin-bottom:1.5rem;">
            Data <strong id="hapusSiswaName"></strong> beserta seluruh absensinya
            akan <strong style="color:#c0392b;">dihapus permanen</strong> dan tidak dapat dikembalikan.
        </p>
        <form id="formHapusSiswa" method="POST">
            @csrf @method('DELETE')
            <div style="display:flex;gap:0.75rem;justify-content:center;">
                <button type="submit"
                    style="padding:10px 28px;background:#c0392b;color:white;border:none;border-radius:10px;font-weight:700;font-size:0.9rem;cursor:pointer;">
                    🗑️ Ya, Hapus
                </button>
                <button type="button" onclick="closeModalHapusSiswa()"
                    style="padding:10px 28px;background:#f5f5f5;color:#333;border:none;border-radius:10px;font-weight:600;font-size:0.9rem;cursor:pointer;">
                    Batal
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
function openModalHapusSiswa(id, nama) {
    document.getElementById('hapusSiswaName').textContent = nama;
    document.getElementById('formHapusSiswa').action = '/admin/siswa/' + id;
    document.getElementById('modalHapusSiswa').style.display = 'flex';
}
function closeModalHapusSiswa() {
    document.getElementById('modalHapusSiswa').style.display = 'none';
}
document.getElementById('modalHapusSiswa').addEventListener('click', function(e) {
    if (e.target === this) closeModalHapusSiswa();
});
</script>
@endpush

@endsection