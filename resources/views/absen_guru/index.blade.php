@extends('layouts.app')

@section('title', 'Absensi Guru')

@push('styles')
<style>
    .modal-overlay {
        display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.45);
        z-index: 999; align-items: center; justify-content: center;
    }
    .modal-overlay.show { display: flex; }
    .status-badge {
        display: inline-block; padding: 3px 12px; border-radius: 20px;
        font-size: 0.72rem; font-weight: 700;
    }
    .status-hadir { background: #E8F5E9; color: #2E7D32; }
    .status-sakit { background: #E3F2FD; color: #1565C0; }
    .status-izin  { background: #FFF8E1; color: #F57F17; }
    .status-alpha { background: var(--red-pale); color: var(--red); }
</style>
@endpush

@section('content')

{{-- MODAL TAMBAH --}}
<div id="modalTambah" class="modal-overlay">
    <div style="background:white;border-radius:var(--radius-xl);padding:2rem;width:100%;max-width:480px;margin:1rem;box-shadow:var(--shadow-lg);max-height:90vh;overflow-y:auto;">
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1.5rem;">
            <h3 style="font-size:1rem;font-weight:800;">➕ Tambah Absensi Guru</h3>
            <button onclick="closeModalTambah()" style="background:var(--gray-100);border:none;border-radius:8px;width:32px;height:32px;cursor:pointer;font-size:16px;">✕</button>
        </div>
        <form method="POST" action="{{ route('admin.absen-guru.store') }}">
            @csrf
            <div class="form-group">
                <label>Guru</label>
                <select name="guru_id" required>
                    <option value="">-- Pilih Guru --</option>
                    @foreach($gurus as $g)
                        <option value="{{ $g->id }}">{{ $g->nama }} ({{ $g->nip ?? '-' }})</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label>Tanggal</label>
                <input type="date" name="tanggal" value="{{ $tanggal }}" required>
            </div>
            <div class="form-group">
                <label>Status</label>
                <select name="status" required>
                    <option value="hadir">Hadir</option>
                    <option value="sakit">Sakit</option>
                    <option value="izin">Izin</option>
                    <option value="alpha">Alpha</option>
                </select>
            </div>
            <div class="form-group">
                <label>Jam Masuk</label>
                <input type="time" name="jam_masuk">
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
        <div style="font-size:48px;margin-bottom:0.75rem;">🗑️</div>
        <h3 style="font-size:1rem;font-weight:800;margin-bottom:0.5rem;">Hapus Absensi?</h3>
        <p style="font-size:0.83rem;color:var(--gray-500);margin-bottom:1.5rem;">Data absensi <strong id="hapusNama"></strong> akan dihapus.</p>
        <form id="formHapus" method="POST">
            @csrf @method('DELETE')
            <div style="display:flex;gap:0.75rem;justify-content:center;">
                <button type="submit" class="btn btn-danger" style="min-width:120px;">🗑️ Hapus</button>
                <button type="button" class="btn btn-secondary" onclick="closeModalHapus()">Batal</button>
            </div>
        </form>
    </div>
</div>

{{-- MAIN CARD --}}
<div class="card">
    <div class="card-header" style="flex-wrap:wrap;gap:0.75rem;">
        <span class="card-title">👨‍🏫 Absensi Guru</span>
        <div style="display:flex;gap:0.5rem;align-items:center;flex-wrap:wrap;">
            <form method="GET" action="{{ route('admin.absen-guru.index') }}" id="filterForm" style="display:flex;gap:0.5rem;align-items:center;">
                <input type="date" name="tanggal" value="{{ $tanggal }}"
                    onchange="document.getElementById('filterForm').submit()"
                    style="padding:0.5rem 0.75rem;border:1.5px solid var(--gray-200);border-radius:10px;font-family:inherit;font-size:0.83rem;outline:none;">
                <input type="text" name="search" placeholder="Cari nama..." value="{{ request('search') }}"
                    style="padding:0.5rem 0.75rem;border:1.5px solid var(--gray-200);border-radius:10px;font-family:inherit;font-size:0.83rem;outline:none;width:160px;">
                <button type="submit" class="btn btn-primary" style="padding:0.5rem 1rem;font-size:0.83rem;">🔍</button>
            </form>
            <button class="btn btn-primary" style="padding:0.5rem 1rem;font-size:0.83rem;" onclick="openModalTambah()">➕ Tambah</button>
        </div>
    </div>

    <div class="card-body" style="padding:0;">
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th style="width:40px;">#</th>
                        <th>Nama Guru</th>
                        <th>NIP</th>
                        <th>Tanggal</th>
                        <th>Jam Masuk</th>
                        <th>Status</th>
                        <th>Keterangan</th>
                        <th style="width:100px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($absensi as $i => $a)
                    <tr>
                        <td style="font-weight:700;color:var(--gray-500);">{{ $i + 1 }}</td>
                        <td style="font-weight:600;">{{ $a->guru->nama ?? '-' }}</td>
                        <td style="font-family:monospace;font-size:0.75rem;color:var(--gray-500);">{{ $a->guru->nip ?? '-' }}</td>
                        <td>{{ \Carbon\Carbon::parse($a->tanggal)->format('d/m/Y') }}</td>
                        <td>{{ $a->jam_masuk ?? '-' }}</td>
                        <td><span class="status-badge status-{{ $a->status }}">{{ ucfirst($a->status) }}</span></td>
                        <td style="font-size:0.82rem;color:var(--gray-500);">{{ $a->keterangan ?? '-' }}</td>
                        <td>
                            <div style="display:flex;gap:6px;">
                                <a href="{{ route('admin.absen-guru.edit', $a->id) }}" class="btn btn-secondary" style="padding:4px 8px;font-size:0.75rem;">✏️</a>
                                <button class="btn btn-danger" style="padding:4px 8px;font-size:0.75rem;"
                                    onclick="openModalHapus({{ $a->id }}, '{{ addslashes($a->guru->nama ?? '') }}')">🗑️</button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" style="text-align:center;padding:2.5rem;color:var(--gray-500);font-size:0.85rem;">
                            Belum ada data absensi guru untuk tanggal ini.
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
function openModalTambah() { document.getElementById('modalTambah').classList.add('show'); }
function closeModalTambah() { document.getElementById('modalTambah').classList.remove('show'); }

function openModalHapus(id, nama) {
    document.getElementById('hapusNama').textContent = nama;
    document.getElementById('formHapus').action = '/admin/absen-guru/' + id;
    document.getElementById('modalHapus').classList.add('show');
}
function closeModalHapus() { document.getElementById('modalHapus').classList.remove('show'); }

document.getElementById('modalTambah').addEventListener('click', function(e) { if(e.target===this) closeModalTambah(); });
document.getElementById('modalHapus').addEventListener('click', function(e) { if(e.target===this) closeModalHapus(); });
</script>
@endpush
