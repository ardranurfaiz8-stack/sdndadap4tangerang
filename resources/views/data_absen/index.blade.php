@extends('layouts.app')

@section('title', 'Absensi Siswa')

@push('styles')
<style>
    /* ── Modal ── */
    .modal-overlay {
        display: none; position: fixed; inset: 0;
        background: rgba(0,0,0,0.45); z-index: 999;
        align-items: center; justify-content: center;
    }
    .modal-overlay.show { display: flex; }

    /* ── Kelas badge ── */
    .kelas-badge {
        display: inline-block; padding: 3px 10px; border-radius: 20px;
        font-size: 0.72rem; font-weight: 700;
        background: var(--red-pale); color: var(--red);
    }

    /* ── Status select ── */
    .status-select {
        padding: 5px 10px; border: 1.5px solid var(--gray-200);
        border-radius: 8px; font-family: inherit; font-size: 0.82rem;
        outline: none; cursor: pointer; background: white;
        transition: border-color 0.2s;
    }
    .status-select.hadir { border-color:#A5D6A7; color:#2E7D32; background:#F1F8F1; }
    .status-select.sakit { border-color:#90CAF9; color:#1565C0; background:#F1F6FB; }
    .status-select.izin  { border-color:#FFE082; color:#F57F17; background:#FFFDF0; }
    .status-select.alpha { border-color:var(--red-mid); color:var(--red); background:var(--red-pale); }

    /* ── Header: judul + filter ── */
    .absen-header {
        display: flex; align-items: center; justify-content: space-between;
        flex-wrap: wrap; gap: 0.75rem;
        padding: 1.1rem 1.5rem;
        border-bottom: 1px solid var(--gray-100);
    }
    .absen-header-title {
        font-size: 1rem; font-weight: 800; color: var(--gray-900);
    }
    .absen-filter-row {
        display: flex; align-items: center; gap: 0.5rem; flex-wrap: wrap;
    }
    .absen-filter-row input[type="date"],
    .absen-filter-row select {
        padding: 7px 12px;
        border: 1.5px solid var(--gray-200); border-radius: 10px;
        font-family: inherit; font-size: 0.82rem;
        color: var(--gray-900); background: white; outline: none; cursor: pointer;
    }
    .absen-filter-row input[type="date"]:focus,
    .absen-filter-row select:focus { border-color: var(--red); }

    /* Search box */
    .search-wrap { position: relative; display: flex; align-items: center; }
    .search-wrap .search-icon {
        position: absolute; left: 10px; font-size: 13px; pointer-events: none;
    }
    .search-wrap input[type="text"] {
        padding: 7px 12px 7px 30px;
        border: 1.5px solid var(--gray-200); border-radius: 10px;
        font-family: inherit; font-size: 0.82rem;
        color: var(--gray-900); background: white; outline: none; width: 170px;
    }
    .search-wrap input[type="text"]:focus { border-color: var(--red); }

    /* ── Baris summary + tombol aksi ── */
    .absen-action-row {
        display: flex; align-items: center; justify-content: space-between;
        flex-wrap: wrap; gap: 0.75rem;
        padding: 1rem 1.5rem;
        border-bottom: 1px solid var(--gray-100);
    }

    /* Summary pills */
    .summary-bar { display: flex; gap: 0.4rem; flex-wrap: wrap; align-items: center; }
    .summary-pill {
        display: inline-flex; align-items: center; gap: 5px;
        padding: 5px 14px; border-radius: 20px;
        font-size: 0.8rem; font-weight: 500;
    }

    /* Tombol Semua Hadir */
    .btn-semua-hadir {
        display: inline-flex; flex-direction: column; align-items: center;
        justify-content: center; gap: 2px;
        padding: 10px 20px; border-radius: 12px;
        background: #E8F5E9; color: #2E7D32;
        border: 1.5px solid #A5D6A7;
        font-family: inherit; font-size: 0.82rem; font-weight: 700;
        cursor: pointer; transition: background 0.15s;
        min-width: 90px; text-align: center; line-height: 1.4;
    }
    .btn-semua-hadir:hover { background: #C8E6C9; }

    /* Tombol merah */
    .btn-absen-red {
        display: inline-flex; align-items: center; justify-content: center; gap: 6px;
        padding: 12px 24px; border-radius: 12px;
        background: var(--red); color: white; border: none;
        font-family: inherit; font-size: 0.88rem; font-weight: 700;
        cursor: pointer; transition: background 0.15s;
        min-width: 150px;
    }
    .btn-absen-red:hover { background: var(--red-dark); }

    /* Tombol ikon tabel */
    .tbl-btn {
        width: 30px; height: 30px;
        display: inline-flex; align-items: center; justify-content: center;
        border: none; border-radius: 7px; cursor: pointer;
        font-size: 13px; transition: background 0.15s; text-decoration: none;
    }
    .tbl-btn.edit  { background: var(--gray-100); color: var(--gray-700); }
    .tbl-btn.edit:hover  { background: var(--gray-200); }
    .tbl-btn.hapus { background: #FFEBEE; color: var(--red); }
    .tbl-btn.hapus:hover { background: var(--red); color: white; }
</style>
@endpush

@section('content')

{{-- ══════════ MODAL TAMBAH SISWA ══════════ --}}
<div id="modalTambahSiswa" class="modal-overlay">
    <div style="background:white;border-radius:var(--radius-xl);padding:2rem;width:100%;max-width:460px;margin:1rem;box-shadow:var(--shadow-lg);max-height:90vh;overflow-y:auto;">
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1.5rem;">
            <h3 style="font-size:1rem;font-weight:800;color:var(--gray-900);">➕ Tambah Siswa ke Absensi</h3>
            <button onclick="closeModalTambah()" style="background:var(--gray-100);border:none;border-radius:8px;width:32px;height:32px;cursor:pointer;font-size:16px;">✕</button>
        </div>
        <form method="POST" action="{{ route('guru.absen_siswa.store') }}">
            @csrf
            <input type="hidden" name="tanggal" value="{{ request('tanggal', date('Y-m-d')) }}">
            <div class="form-group">
                <label>Siswa</label>
                <select name="siswa_id" required>
                    <option value="">-- Pilih Siswa --</option>
                    @foreach($semuaSiswa as $s)
                        <option value="{{ $s->id }}">{{ $s->nama }} ({{ $s->kelas ?? '-' }})</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label>Status</label>
                <select name="status" required>
                    <option value="hadir">✅ Hadir</option>
                    <option value="sakit">🏥 Sakit</option>
                    <option value="izin">📝 Izin</option>
                    <option value="alpha">❌ Alpha</option>
                </select>
            </div>
            <div class="form-group">
                <label>Keterangan</label>
                <textarea name="keterangan" rows="2" placeholder="Keterangan (opsional)..."></textarea>
            </div>
            <div style="display:flex;gap:0.75rem;">
                <button type="submit" class="btn btn-primary" style="flex:1;">💾 Simpan</button>
                <button type="button" class="btn btn-secondary" onclick="closeModalTambah()">Batal</button>
            </div>
        </form>
    </div>
</div>

{{-- ══════════ MODAL HAPUS ══════════ --}}
<div id="modalHapus" class="modal-overlay">
    <div style="background:white;border-radius:var(--radius-xl);padding:2rem;width:100%;max-width:380px;margin:1rem;box-shadow:var(--shadow-lg);text-align:center;">
        <div style="font-size:48px;margin-bottom:0.75rem;">🗑️</div>
        <h3 style="font-size:1rem;font-weight:800;color:var(--gray-900);margin-bottom:0.5rem;">Hapus Absensi?</h3>
        <p style="font-size:0.83rem;color:var(--gray-500);margin-bottom:1.5rem;">
            Data absensi <strong id="hapusNama"></strong> akan dihapus permanen.
        </p>
        <form id="formHapus" method="POST">
            @csrf @method('DELETE')
            <div style="display:flex;gap:0.75rem;justify-content:center;">
                <button type="submit" class="btn btn-danger" style="min-width:120px;">🗑️ Hapus</button>
                <button type="button" class="btn btn-secondary" onclick="closeModalHapus()">Batal</button>
            </div>
        </form>
    </div>
</div>

{{-- ══════════ MAIN CARD ══════════ --}}
<div class="card">

    {{-- Baris 1: Judul + Filter --}}
    <div class="absen-header">
        <span class="absen-header-title">👨‍🏫 Daftar &amp; Absensi Siswa</span>

        <form method="GET" action="{{ route('guru.absen_siswa.index') }}" id="filterForm" class="absen-filter-row">
            <input type="date" name="tanggal"
                value="{{ request('tanggal', date('Y-m-d')) }}"
                onchange="this.form.submit()">

            <select name="kelas" onchange="this.form.submit()">
                <option value="">Semua Kelas</option>
                @foreach($kelasList as $kelas)
                    <option value="{{ $kelas }}" {{ request('kelas')==$kelas ? 'selected':'' }}>{{ $kelas }}</option>
                @endforeach
            </select>

            <div class="search-wrap">
                <span class="search-icon">🔍</span>
                <input type="text" name="search"
                    placeholder="Cari nama..."
                    value="{{ request('search') }}"
                    onchange="this.form.submit()">
            </div>
        </form>
    </div>

    {{-- Baris 2: Summary + Tombol aksi --}}
    <div class="absen-action-row">

        <div class="summary-bar">
            <span class="summary-pill" style="background:#E8F5E9;color:#2E7D32;">✅ Hadir: <strong>{{ $summary['hadir'] }}</strong></span>
            <span class="summary-pill" style="background:#E3F2FD;color:#1565C0;">🏥 Sakit: <strong>{{ $summary['sakit'] }}</strong></span>
            <span class="summary-pill" style="background:#FFF8E1;color:#F57F17;">📝 Izin: <strong>{{ $summary['izin'] }}</strong></span>
            <span class="summary-pill" style="background:var(--red-pale);color:var(--red);">❌ Alpha: <strong>{{ $summary['alpha'] }}</strong></span>
        </div>

        <div style="display:flex;gap:0.6rem;align-items:stretch;flex-wrap:wrap;">

            {{-- Semua Hadir --}}
            <form method="POST" action="{{ route('guru.absen_siswa.store') }}">
                @csrf
                <input type="hidden" name="tanggal" value="{{ request('tanggal', date('Y-m-d')) }}">
                <input type="hidden" name="bulk_hadir" value="1">
                <input type="hidden" name="kelas" value="{{ request('kelas') }}">
                <button type="submit" class="btn-semua-hadir">
                    <span style="font-size:20px;">✅</span>
                    Semua Hadir
                </button>
            </form>

            {{-- Tambah Siswa --}}
            <button type="button" class="btn-absen-red" onclick="openModalTambah()">
                ＋ Tambah Siswa
            </button>

            {{-- Simpan Absen --}}
            <form method="POST" action="{{ route('guru.absen_siswa.store') }}" id="formSimpanAbsen">
                @csrf
                <input type="hidden" name="tanggal" value="{{ request('tanggal', date('Y-m-d')) }}">
                <input type="hidden" name="bulk_save" value="1">
                <div id="hiddenStatusFields"></div>
                <button type="button" class="btn-absen-red" onclick="simpanAbsen()">
                    💾 Simpan Absen
                </button>
            </form>

        </div>
    </div>

    {{-- Tabel --}}
    <div style="padding:0 1.5rem 1.5rem;">
        <div class="table-wrap" style="margin-top:0.75rem;">
            <table>
                <thead>
                    <tr>
                        <th style="width:44px;">#</th>
                        <th>Nama Siswa</th>
                        <th>NIS</th>
                        <th>Kelas</th>
                        <th>Status Absen</th>
                        <th style="width:90px;text-align:center;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($siswaList as $i => $siswa)
                    @php
                        $absen         = $absensiMap[$siswa->id] ?? null;
                        $currentStatus = $absen ? $absen->status : 'hadir';
                    @endphp
                    <tr>
                        <td style="font-weight:700;color:var(--gray-500);">{{ $i + 1 }}</td>
                        <td style="font-weight:600;font-size:0.88rem;color:var(--gray-900);">{{ $siswa->nama }}</td>
                        <td style="font-family:monospace;font-size:0.75rem;color:var(--gray-500);">{{ $siswa->nis ?? '-' }}</td>
                        <td><span class="kelas-badge">{{ $siswa->kelas ?? '-' }}</span></td>
                        <td>
                            <select class="status-select {{ $currentStatus }}"
                                id="status_{{ $siswa->id }}"
                                data-siswa-id="{{ $siswa->id }}"
                                onchange="updateStatusStyle(this)">
                                <option value="hadir" {{ $currentStatus=='hadir'?'selected':'' }}>✅ Hadir</option>
                                <option value="sakit" {{ $currentStatus=='sakit'?'selected':'' }}>🏥 Sakit</option>
                                <option value="izin"  {{ $currentStatus=='izin' ?'selected':'' }}>📝 Izin</option>
                                <option value="alpha" {{ $currentStatus=='alpha'?'selected':'' }}>❌ Alpha</option>
                            </select>
                        </td>
                        <td>
                            <div style="display:flex;gap:6px;align-items:center;justify-content:center;">
                                {{-- tidak ada route show untuk guru, pakai update --}}
                                <button class="tbl-btn edit" title="Edit"
                                    onclick="openModalEdit({{ $siswa->id }}, '{{ $currentStatus }}')">✏️</button>
                                @if($absen)
                                <button class="tbl-btn hapus"
                                    onclick="openModalHapus({{ $absen->id }}, '{{ addslashes($siswa->nama) }}')"
                                    title="Hapus">🗑️</button>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" style="text-align:center;padding:2.5rem;color:var(--gray-500);font-size:0.85rem;">
                            📭 Tidak ada siswa ditemukan
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
function updateStatusStyle(sel) {
    sel.className = 'status-select ' + sel.value;
}
document.querySelectorAll('.status-select').forEach(updateStatusStyle);

function simpanAbsen() {
    const container = document.getElementById('hiddenStatusFields');
    container.innerHTML = '';
    document.querySelectorAll('.status-select').forEach(function(sel) {
        const inp = document.createElement('input');
        inp.type  = 'hidden';
        inp.name  = 'statuses[' + sel.dataset.siswaId + ']';
        inp.value = sel.value;
        container.appendChild(inp);
    });
    document.getElementById('formSimpanAbsen').submit();
}

function openModalTambah()  { document.getElementById('modalTambahSiswa').classList.add('show'); }
function closeModalTambah() { document.getElementById('modalTambahSiswa').classList.remove('show'); }

function openModalHapus(absenId, nama) {
    document.getElementById('hapusNama').textContent = nama;
    document.getElementById('formHapus').action = '/guru/absen_siswa/' + absenId;
    document.getElementById('modalHapus').classList.add('show');
}
function closeModalHapus() { document.getElementById('modalHapus').classList.remove('show'); }

document.getElementById('modalTambahSiswa').addEventListener('click', function(e){ if(e.target===this) closeModalTambah(); });
document.getElementById('modalHapus').addEventListener('click',       function(e){ if(e.target===this) closeModalHapus(); });
</script>
@endpush