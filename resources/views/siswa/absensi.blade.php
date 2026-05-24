@extends('layouts.app')

@section('title', 'Absensi Saya')

@section('content')

{{-- Info Mode --}}
<div style="background:var(--red-pale);border:1px solid var(--red-mid);border-radius:var(--radius);padding:0.75rem 1rem;margin-bottom:1.25rem;font-size:0.85rem;color:var(--red);display:flex;align-items:center;gap:8px;">
    👁️ Mode Lihat Saja — Anda hanya dapat melihat data absensi
</div>

{{-- Card Utama --}}
<div class="card">
    {{-- Header + Filter --}}
    <div class="card-header" style="flex-wrap:wrap;gap:0.75rem;">
        <span class="card-title">👨‍🎓 Daftar & Absensi Siswa</span>
        <div style="display:flex;align-items:center;gap:0.5rem;flex-wrap:wrap;">
            {{-- Filter Tanggal --}}
            <input type="date" id="filterTanggal"
                value="{{ $tanggal }}"
                style="padding:0.45rem 0.75rem;border:1.5px solid var(--gray-200);border-radius:8px;font-family:inherit;font-size:0.8rem;color:var(--gray-900);outline:none;cursor:pointer;">

            {{-- Filter Kelas --}}
            <select id="filterKelas"
                style="padding:0.45rem 0.75rem;border:1.5px solid var(--gray-200);border-radius:8px;font-family:inherit;font-size:0.8rem;color:var(--gray-900);outline:none;cursor:pointer;">
                <option value="">Semua Kelas</option>
                @foreach($kelasList as $kelas)
                    <option value="{{ $kelas }}" {{ $kelasFilter === $kelas ? 'selected' : '' }}>{{ $kelas }}</option>
                @endforeach
            </select>

            {{-- Search --}}
            <div style="position:relative;">
                <span style="position:absolute;left:10px;top:50%;transform:translateY(-50%);font-size:14px;">🔍</span>
                <input type="text" id="searchNama" placeholder="Cari nama..."
                    value="{{ $search }}"
                    style="padding:0.45rem 0.75rem 0.45rem 2rem;border:1.5px solid var(--gray-200);border-radius:8px;font-family:inherit;font-size:0.8rem;color:var(--gray-900);outline:none;width:180px;">
            </div>
        </div>
    </div>

    {{-- Counter Badge --}}
    <div style="padding:0.9rem 1.5rem;border-bottom:1px solid var(--gray-100);display:flex;gap:0.75rem;flex-wrap:wrap;">
        <span style="background:#E8F5E9;color:#2E7D32;padding:4px 14px;border-radius:20px;font-size:0.8rem;font-weight:700;">
            ✅ Hadir: {{ $counter['hadir'] }}
        </span>
        <span style="background:#E3F2FD;color:#1565C0;padding:4px 14px;border-radius:20px;font-size:0.8rem;font-weight:700;">
            🏥 Sakit: {{ $counter['sakit'] }}
        </span>
        <span style="background:#FFF8E1;color:#F57F17;padding:4px 14px;border-radius:20px;font-size:0.8rem;font-weight:700;">
            📋 Izin: {{ $counter['izin'] }}
        </span>
        <span style="background:var(--red-pale);color:var(--red);padding:4px 14px;border-radius:20px;font-size:0.8rem;font-weight:700;">
            ✖ Alpha: {{ $counter['alpha'] }}
        </span>
    </div>

    {{-- Tabel --}}
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nama Siswa</th>
                    <th>NIS</th>
                    <th>Kelas</th>
                    <th>Status Absen</th>
                </tr>
            </thead>
            <tbody>
                @forelse($siswas as $i => $s)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td style="font-weight:600;color:var(--gray-900);">{{ $s['nama'] }}</td>
                    <td style="color:var(--gray-500);font-size:0.8rem;">{{ $s['nis'] }}</td>
                    <td>
                        <span style="background:var(--red-pale);color:var(--red);padding:2px 10px;border-radius:20px;font-size:0.75rem;font-weight:700;">
                            {{ $s['kelas'] }}
                        </span>
                    </td>
                    <td>
                        @php $status = $s['status']; @endphp
                        @if($status)
                            <span style="display:inline-flex;align-items:center;gap:6px;padding:5px 14px;border-radius:8px;font-size:0.8rem;font-weight:600;
                                {{ $status === 'hadir' ? 'background:#E8F5E9;color:#2E7D32;' : '' }}
                                {{ $status === 'sakit' ? 'background:#E3F2FD;color:#1565C0;' : '' }}
                                {{ $status === 'izin'  ? 'background:#FFF8E1;color:#F57F17;' : '' }}
                                {{ $status === 'alpha' ? 'background:var(--red-pale);color:var(--red);' : '' }}
                            ">
                                {{ $status === 'hadir' ? '✅' : ($status === 'sakit' ? '🏥' : ($status === 'izin' ? '📋' : '✖')) }}
                                {{ ucfirst($status) }}
                            </span>
                        @else
                            <span style="color:var(--gray-400);font-size:0.8rem;">— Belum absen</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" style="text-align:center;padding:2.5rem;color:var(--gray-500);">
                        <div style="font-size:2rem;margin-bottom:0.5rem;">📭</div>
                        <div style="font-weight:600;">Tidak ada data siswa</div>
                        <div style="font-size:0.78rem;margin-top:4px;">Coba ubah filter tanggal atau kelas</div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection

@push('scripts')
<script>
    // Auto-submit filter saat berubah
    document.getElementById('filterTanggal').addEventListener('change', applyFilter);
    document.getElementById('filterKelas').addEventListener('change', applyFilter);

    // Search dengan delay
    let searchTimer;
    document.getElementById('searchNama').addEventListener('input', function() {
        clearTimeout(searchTimer);
        searchTimer = setTimeout(applyFilter, 400);
    });

    function applyFilter() {
        const tanggal = document.getElementById('filterTanggal').value;
        const kelas   = document.getElementById('filterKelas').value;
        const search  = document.getElementById('searchNama').value;
        const params  = new URLSearchParams({ tanggal, kelas, search });
        window.location.href = '{{ route("siswa.absensi.index") }}?' + params.toString();
    }
</script>
@endpush