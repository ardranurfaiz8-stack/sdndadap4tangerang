@extends('layouts.app')

@section('title', 'Dashboard Guru')

@section('content')

{{-- ===== STAT CARDS ===== --}}
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon green">
            <img src="https://img.icons8.com/color/48/checked-checkbox.png" width="32" height="32" alt="hadir">
        </div>
        <div>
            <div class="stat-value">{{ $rekapBulan['hadir'] ?? 0 }}</div>
            <div class="stat-label">Hadir Bulan Ini</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon blue">
            <img src="https://img.icons8.com/color/48/heart-with-pulse.png" width="32" height="32" alt="sakit">
        </div>
        <div>
            <div class="stat-value">{{ $rekapBulan['sakit'] ?? 0 }}</div>
            <div class="stat-label">Sakit Bulan Ini</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon amber">
            <img src="https://img.icons8.com/color/48/document.png" width="32" height="32" alt="izin">
        </div>
        <div>
            <div class="stat-value">{{ $rekapBulan['izin'] ?? 0 }}</div>
            <div class="stat-label">Izin Bulan Ini</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon red">
            <img src="https://img.icons8.com/color/48/multiply.png" width="32" height="32" alt="alpha">
        </div>
        <div>
            <div class="stat-value">{{ $rekapBulan['alpha'] ?? 0 }}</div>
            <div class="stat-label">Alpha Bulan Ini</div>
        </div>
    </div>
</div>

{{-- ===== STATUS ABSENSI HARI INI ===== --}}
<div class="card" style="margin-top:1.5rem;">
    <div class="card-header">
        <span class="card-title">📋 Status Absensi Hari Ini</span>
        <span style="font-size:0.78rem;color:var(--gray-500);">
            Hari ini, {{ \Carbon\Carbon::today()->translatedFormat('d F Y') }}
        </span>
    </div>
    <div class="card-body">
        @if($absenHariIni)
            <div style="display:flex;align-items:center;gap:20px;flex-wrap:wrap;">
                <div style="display:flex;align-items:center;gap:8px;">
                    <span style="font-size:0.85rem;font-weight:600;color:var(--gray-700);">Status:</span>
                    @php $s = strtolower($absenHariIni->status); @endphp
                    <span class="badge badge-{{ $s }}">{{ ucfirst($s) }}</span>
                </div>
                @if($absenHariIni->jam_masuk)
                <div style="display:flex;align-items:center;gap:8px;">
                    <span style="font-size:0.85rem;font-weight:600;color:var(--gray-700);">Jam Masuk:</span>
                    <span style="font-size:0.85rem;color:var(--gray-900);font-weight:700;">{{ $absenHariIni->jam_masuk }}</span>
                </div>
                @endif
                @if($absenHariIni->keterangan)
                <div style="display:flex;align-items:center;gap:8px;">
                    <span style="font-size:0.85rem;font-weight:600;color:var(--gray-700);">Keterangan:</span>
                    <span style="font-size:0.85rem;color:var(--gray-600);">{{ $absenHariIni->keterangan }}</span>
                </div>
                @endif
            </div>
        @else
            <div style="text-align:center;padding:2rem;color:var(--gray-500);font-size:0.85rem;">
                🖨️ Belum ada absensi hari ini
            </div>
        @endif
    </div>
</div>

{{-- ===== RIWAYAT + RINGKASAN ===== --}}
<div class="dash-grid" style="margin-top:1.5rem;">

    {{-- Riwayat Absensi --}}
    <div class="card">
        <div class="card-header">
            <span class="card-title">🕐 Riwayat Absensi Terbaru</span>
        </div>
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Tanggal</th>
                        <th>Jam Masuk</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($absensiTerbaru ?? [] as $i => $ab)
                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td>{{ $ab->tanggal ? \Carbon\Carbon::parse($ab->tanggal)->translatedFormat('d M Y') : '-' }}</td>
                        <td>{{ $ab->jam_masuk ?? '-' }}</td>
                        <td>
                            @php $s = strtolower($ab->status ?? ''); @endphp
                            <span class="badge badge-{{ $s }}">{{ ucfirst($s) }}</span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" style="text-align:center;padding:1.5rem;color:var(--gray-500);">Belum ada data absensi</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Ringkasan Bulan Ini --}}
    <div class="card">
        <div class="card-header">
            <span class="card-title">📊 Ringkasan Bulan Ini</span>
            <span style="font-size:0.75rem;color:var(--gray-500);">
                {{ \Carbon\Carbon::today()->translatedFormat('F Y') }}
            </span>
        </div>
        <div class="card-body">
            <div style="display:flex;flex-direction:column;gap:0.6rem;">
                <div style="display:flex;align-items:center;justify-content:space-between;padding:0.7rem 1rem;background:var(--gray-50);border-radius:10px;border:1px solid var(--gray-200);">
                    <div style="display:flex;align-items:center;gap:10px;font-size:0.85rem;font-weight:600;color:var(--gray-700);">
                        <img src="https://img.icons8.com/color/32/checked-checkbox.png" width="20" height="20"> Hadir
                    </div>
                    <span style="font-weight:800;font-size:0.9rem;color:#2E7D32;">{{ $rekapBulan['hadir'] ?? 0 }}</span>
                </div>
                <div style="display:flex;align-items:center;justify-content:space-between;padding:0.7rem 1rem;background:var(--gray-50);border-radius:10px;border:1px solid var(--gray-200);">
                    <div style="display:flex;align-items:center;gap:10px;font-size:0.85rem;font-weight:600;color:var(--gray-700);">
                        <img src="https://img.icons8.com/color/32/heart-with-pulse.png" width="20" height="20"> Sakit
                    </div>
                    <span style="font-weight:800;font-size:0.9rem;color:#1565C0;">{{ $rekapBulan['sakit'] ?? 0 }}</span>
                </div>
                <div style="display:flex;align-items:center;justify-content:space-between;padding:0.7rem 1rem;background:var(--gray-50);border-radius:10px;border:1px solid var(--gray-200);">
                    <div style="display:flex;align-items:center;gap:10px;font-size:0.85rem;font-weight:600;color:var(--gray-700);">
                        <img src="https://img.icons8.com/color/32/document.png" width="20" height="20"> Izin
                    </div>
                    <span style="font-weight:800;font-size:0.9rem;color:#F57F17;">{{ $rekapBulan['izin'] ?? 0 }}</span>
                </div>
                <div style="display:flex;align-items:center;justify-content:space-between;padding:0.7rem 1rem;background:var(--gray-50);border-radius:10px;border:1px solid var(--gray-200);">
                    <div style="display:flex;align-items:center;gap:10px;font-size:0.85rem;font-weight:600;color:var(--gray-700);">
                        <img src="https://img.icons8.com/color/32/multiply.png" width="20" height="20"> Alpha
                    </div>
                    <span style="font-weight:800;font-size:0.9rem;color:var(--red);">{{ $rekapBulan['alpha'] ?? 0 }}</span>
                </div>
            </div>

            @php $pct = $persentaseHadir ?? 0; @endphp
            <div style="margin-top:1.25rem;">
                <div style="display:flex;justify-content:space-between;font-size:0.78rem;color:var(--gray-500);margin-bottom:6px;">
                    <span>Tingkat Kehadiran</span>
                    <span style="font-weight:700;color:#2E7D32;">{{ $pct }}%</span>
                </div>
                <div style="background:var(--gray-200);border-radius:20px;height:8px;overflow:hidden;">
                    <div style="width:{{ $pct }}%;height:100%;background:linear-gradient(90deg,#2ecc71,#27ae60);border-radius:20px;"></div>
                </div>
                <div style="font-size:0.72rem;color:var(--gray-500);margin-top:4px;">
                    {{ $rekapBulan['hadir'] ?? 0 }} dari {{ $hariKerja ?? 22 }} hari kerja
                </div>
            </div>
        </div>
    </div>

</div>

@endsection