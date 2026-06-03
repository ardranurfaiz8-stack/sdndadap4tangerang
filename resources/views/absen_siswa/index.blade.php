@extends('layouts.app')

@section('title', 'Absensi Siswa')

@push('styles')
<style>
    .modal-overlay {
        display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.45);
        z-index: 999; align-items: center; justify-content: center;
    }
    .modal-overlay.show { display: flex; }

    .kelas-badge {
        display: inline-block; padding: 3px 10px; border-radius: 20px;
        font-size: 0.72rem; font-weight: 700;
        background: var(--red-pale); color: var(--red);
    }
    .status-select {
        padding: 5px 10px; border: 1.5px solid var(--gray-200); border-radius: 8px;
        font-family: inherit; font-size: 0.78rem; outline: none; cursor: pointer; background: white;
        transition: border-color 0.2s;
    }
    .status-select:focus { border-color: var(--red); }
    .status-select.hadir  { border-color: #A5D6A7; color: #2E7D32; background: #F1F8F1; }
    .status-select.sakit  { border-color: #90CAF9; color: #1565C0; background: #F1F6FB; }
    .status-select.izin   { border-color: #FFE082; color: #F57F17; background: #FFFDF0; }
    .status-select.alpha  { border-color: var(--red-mid); color: var(--red); background: var(--red-pale); }
    .status-select.belum  { border-color: var(--gray-300); color: var(--gray-500); background: var(--gray-50); }

    /* Summary pills */
    .summary-bar {
        display: flex; gap: 0.5rem; align-items: center; flex-wrap: wrap;
    }
    .summary-pill {
        display: inline-flex; align-items: center; gap: 4px;
        padding: 5px 14px; border-radius: 20px; font-size: 0.8rem; font-weight: 500;
    }

    /* Action buttons row */
    .action-row {
        display: flex; gap: 0.5rem; align-items: center; flex-wrap: wrap;
    }

    /* Btn overrides to match screenshot */
    .btn-semua-hadir {
        background: #E8F5E9; color: #2E7D32;
        border: 1.5px solid #A5D6A7; border-radius: 10px;
        padding: 8px 18px; font-size: 0.83rem; font-weight: 600;
        cursor: pointer; display: inline-flex; align-items: center; gap: 6px;
        transition: background 0.15s;
    }
    .btn-semua-hadir:hover { background: #C8E6C9; }

    .btn-tambah-siswa {
        background: #c0392b; color: white;
        border: none; border-radius: 10px;
        padding: 8px 18px; font-size: 0.83rem; font-weight: 600;
        cursor: pointer; display: inline-flex; align-items: center; gap: 6px;
        transition: background 0.15s;
    }
    .btn-tambah-siswa:hover { background: #a93226; }

    .btn-simpan-absen {
        background: #c0392b; color: white;
        border: none; border-radius: 10px;
        padding: 8px 18px; font-size: 0.83rem; font-weight: 600;
        cursor: pointer; display: inline-flex; align-items: center; gap: 6px;
        transition: background 0.15s;
    }
    .btn-simpan-absen:hover { background: #a93226; }

    /* Search box */
    .search-input-wrap {
        position: relative; display: flex; align-items: center;
    }
    .search-input-wrap input {
        padding: 8px 12px 8px 34px;
        border: 1.5px solid var(--gray-200); border-radius: 10px;
        font-family: inherit; font-size: 0.83rem; outline: none;
        color: var(--gray-900); width: 180px;
    }
    .search-input-wrap .search-icon {
        position: absolute; left: 10px; font-size: 14px; pointer-events: none;
    }

    /* Header filter row */
    .card-header-top {
        display: flex; align-items: center; justify-content: space-between;
        flex-wrap: wrap; gap: 0.75rem;
        padding: 1rem 1.25rem;
        border-bottom: 1.5px solid var(--gray-100);
    }
    .card-title-text {
        font-size: 1.05rem; font-weight: 800; color: var(--gray-900);
    }
    .filter-row {
        display: flex; gap: 0.5rem; align-items: center; flex-wrap: wrap;
    }
    .filter-date, .filter-select {
        padding: 7px 12px;
        border: 1.5px solid var(--gray-200); border-radius: 10px;
        font-family: inherit; font-size: 0.83rem; outline: none; color: var(--gray-900);
        cursor: pointer;
    }

    /* Summary + action combined row */
    .absensi-top-row {
        display: flex; align-items: center; justify-content: space-between;
        flex-wrap: wrap; gap: 0.75rem;
        padding: 1rem 1.25rem;
        border-bottom: 1.5px solid var(--gray-100);
    }

    /* Aksi icon buttons */
    .btn-icon {
        background: var(--gray-100); border: none; border-radius: 7px;
        width: 30px; height: 30px; display: inline-flex; align-items: center;
        justify-content: center; cursor: pointer; font-size: 14px;
        transition: background 0.15s;
    }
    .btn-icon:hover { background: var(--gray-200); }
    .btn-icon.danger { background: #FFEBEE; }
    .btn-icon.danger:hover { background: #FFCDD2; }
</style>
@endpush

@section('content')

{{-- MODAL TAMBAH SISWA --}}
<div id="modalTambahSiswa" class="modal-overlay">
    <div style="background:white;border-radius:var(--radius-xl);padding:2rem;width:100%;max-width:460px;margin:1rem;box-shadow:var(--shadow-lg);max-height:90vh;overflow-y:auto;">
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1.5rem;">
            <h3 style="font-size:1rem;font-weight:800;color:var(--gray-900);">➕ Tambah Siswa ke Absensi</h3>
            <button onclick="closeModalTambah()" style="background:var(--gray-100);border:none;border-radius:8px;width:32px;height:32px;cursor:pointer;font-size:16px;">✕</button>
        </div>
        <form method="POST" action="{{ route('admin.absen-siswa.store') }}">
            @csrf
            <input type="hidden" name="tanggal" value="{{ request('tanggal', date('Y-m-d')) }}">
            <div class="form-row">
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

{{-- MODAL HAPUS --}}
<div id="modalHapus" class="modal-overlay">
    <div style="background:white;border-radius:var(--radius-xl);padding:2rem;width:100%;max-width:380px;margin:1rem;box-shadow:var(--shadow-lg);text-align:center;">
        <div style="font-size:48px;margin-bottom:0.75rem;">🔄</div>
        <h3 style="font-size:1rem;font-weight:800;color:var(--gray-900);margin-bottom:0.5rem;">Batalkan Absensi?</h3>
        <p style="font-size:0.83rem;color:var(--gray-500);margin-bottom:1.5rem;">Catatan absensi <strong id="hapusNama"></strong> untuk tanggal ini akan dibatalkan/dihapus. Data siswa tetap aman.</p>
        <form id="formHapus" method="POST">
            @csrf @method('DELETE')
            <div style="display:flex;gap:0.75rem;justify-content:center;">
                <button type="submit" class="btn btn-danger" style="min-width:120px;background:#e67e22;border-color:#d35400;">🔄 Batalkan</button>
                <button type="button" class="btn btn-secondary" onclick="closeModalHapus()">Tutup</button>
            </div>
        </form>
    </div>
</div>

{{-- MAIN CARD --}}
<div class="card">

    {{-- Header: Judul + Filter --}}
    <div class="card-header-top">
        <span class="card-title-text">👨‍🏫 Daftar &amp; Absensi Siswa</span>
        <form method="GET" action="{{ route('admin.absen-siswa.index') }}" id="filterForm" class="filter-row">
            <input type="date" name="tanggal" value="{{ request('tanggal', date('Y-m-d')) }}"
                onchange="document.getElementById('filterForm').submit()"
                class="filter-date">
            <select name="kelas" onchange="document.getElementById('filterForm').submit()"
                class="filter-select">
                <option value="">Semua Kelas</option>
                @foreach($kelasList as $kelas)
                    <option value="{{ $kelas }}" {{ request('kelas') == $kelas ? 'selected' : '' }}>{{ $kelas }}</option>
                @endforeach
            </select>
            <div class="search-input-wrap">
                <span class="search-icon">🔍</span>
                <input type="text" name="search" placeholder="Cari nama..."
                    value="{{ request('search') }}"
                    onchange="document.getElementById('filterForm').submit()">
            </div>
        </form>
    </div>

    {{-- Summary + Action Buttons --}}
    <div class="absensi-top-row">
        {{-- Summary pills --}}
        <div class="summary-bar">
            <span class="summary-pill" style="background:#E8F5E9;color:#2E7D32;">✅ Hadir: <strong>{{ $summary['hadir'] }}</strong></span>
            <span class="summary-pill" style="background:#E3F2FD;color:#1565C0;">🏥 Sakit: <strong>{{ $summary['sakit'] }}</strong></span>
            <span class="summary-pill" style="background:#FFF8E1;color:#F57F17;">📝 Izin: <strong>{{ $summary['izin'] }}</strong></span>
            <span class="summary-pill" style="background:var(--red-pale);color:var(--red);">❌ Alpha: <strong>{{ $summary['alpha'] }}</strong></span>
        </div>

        {{-- Action buttons --}}
        <div class="action-row">
            {{-- Semua Hadir --}}
            <form method="POST" action="{{ route('admin.absen-siswa.store') }}" id="formSemuaHadir">
                @csrf
                <input type="hidden" name="tanggal" value="{{ request('tanggal', date('Y-m-d')) }}">
                <input type="hidden" name="bulk_hadir" value="1">
                <input type="hidden" name="kelas" value="{{ request('kelas') }}">
                <button type="submit" class="btn-semua-hadir">✅ Semua Hadir</button>
            </form>

            {{-- Tambah Siswa --}}
            <button class="btn-tambah-siswa" onclick="openModalTambah()">+ Tambah Siswa</button>

            {{-- Simpan Absen --}}
            <form method="POST" action="{{ route('admin.absen-siswa.store') }}" id="formSimpanAbsen">
                @csrf
                <input type="hidden" name="tanggal" value="{{ request('tanggal', date('Y-m-d')) }}">
                <input type="hidden" name="bulk_save" value="1">
                <div id="hiddenStatusFields"></div>
                <button type="button" class="btn-simpan-absen" onclick="simpanAbsen()">💾 Simpan Absen</button>
            </form>
        </div>
    </div>

    {{-- Table --}}
    <div class="card-body" style="padding-top:0;">
        <div class="table-wrap" style="margin-top:0.75rem;">
            <table>
                <thead>
                    <tr>
                        <th style="width:40px;">#</th>
                        <th>NAMA SISWA</th>
                        <th>NIS</th>
                        <th>KELAS</th>
                        <th>STATUS ABSEN</th>
                        <th style="width:90px;">AKSI</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($absensiList as $i => $absen)
                    @php
                        $siswa = $absen->siswa;
                        $currentStatus = $absen->status;
                    @endphp
                    <tr>
                        <td style="font-weight:700;color:var(--gray-500);">{{ $i + 1 }}</td>
                        <td style="font-weight:600;font-size:0.88rem;color:var(--gray-900);">{{ $siswa->nama }}</td>
                        <td><span style="font-family:monospace;font-size:0.75rem;color:var(--gray-500);">{{ $siswa->nis ?? '-' }}</span></td>
                        <td><span class="kelas-badge">{{ $siswa->kelas ?? '-' }}</span></td>
                        <td>
                            <select class="status-select {{ $currentStatus ? $currentStatus : 'belum' }}"
                                id="status_{{ $siswa->id }}"
                                data-siswa-id="{{ $siswa->id }}"
                                onchange="updateStatusStyle(this)">
                                <option value="" {{ $currentStatus == '' ? 'selected' : '' }}>⚪ Belum Absen</option>
                                <option value="hadir" {{ $currentStatus == 'hadir' ? 'selected' : '' }}>✅ Hadir</option>
                                <option value="sakit" {{ $currentStatus == 'sakit' ? 'selected' : '' }}>🏥 Sakit</option>
                                <option value="izin"  {{ $currentStatus == 'izin'  ? 'selected' : '' }}>📝 Izin</option>
                                <option value="alpha" {{ $currentStatus == 'alpha' ? 'selected' : '' }}>❌ Alpha</option>
                            </select>
                        </td>
                        <td>
                            <div style="display:flex;gap:6px;align-items:center;">
                                <a href="{{ route('admin.absen-siswa.show', $siswa->id) }}"
                                    class="btn-icon" title="Detail">✏️</a>
                                <button class="btn-icon danger" style="background:#FFF3E0;color:#E65100;"
                                    onclick="openModalHapus({{ $absen->id }}, '{{ addslashes($siswa->nama) }}')"
                                    title="Batalkan Absen Hari Ini">🔄</button>
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
const tanggal = '{{ request('tanggal', date('Y-m-d')) }}';

function updateStatusStyle(sel) {
    const val = sel.value || 'belum';
    sel.className = 'status-select ' + val;
}

document.querySelectorAll('.status-select').forEach(function(sel) {
    updateStatusStyle(sel);
});

function simpanAbsen() {
    const container = document.getElementById('hiddenStatusFields');
    container.innerHTML = '';
    document.querySelectorAll('.status-select').forEach(function(sel) {
        const siswaId = sel.getAttribute('data-siswa-id');
        const status  = sel.value;
        const f1 = document.createElement('input');
        f1.type  = 'hidden';
        f1.name  = 'statuses[' + siswaId + ']';
        f1.value = status;
        container.appendChild(f1);
    });
    document.getElementById('formSimpanAbsen').submit();
}

function openModalTambah() {
    document.getElementById('modalTambahSiswa').classList.add('show');
}
function closeModalTambah() {
    document.getElementById('modalTambahSiswa').classList.remove('show');
}

function openModalHapus(absenId, nama) {
    document.getElementById('hapusNama').textContent = nama;
    document.getElementById('formHapus').action = '/admin/absen-siswa/' + absenId;
    document.getElementById('modalHapus').classList.add('show');
}
function closeModalHapus() {
    document.getElementById('modalHapus').classList.remove('show');
}

document.getElementById('modalTambahSiswa').addEventListener('click', function(e) { if(e.target===this) closeModalTambah(); });
document.getElementById('modalHapus').addEventListener('click', function(e) { if(e.target===this) closeModalHapus(); });
</script>
@endpush