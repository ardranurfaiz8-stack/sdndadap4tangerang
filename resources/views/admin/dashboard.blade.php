@extends('layouts.app')

@section('title', 'Dashboard Admin')

@section('content')

{{-- ===== STAT CARDS ===== --}}
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon red">👨‍🏫</div>
        <div>
            <div class="stat-value">{{ $totalGuru }}</div>
            <div class="stat-label">Total Guru</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon blue">👨‍🎓</div>
        <div>
            <div class="stat-value">{{ $totalSiswa }}</div>
            <div class="stat-label">Total Siswa</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon green">✅</div>
        <div>
            <div class="stat-value">{{ $hadirHariIni }}</div>
            <div class="stat-label">Hadir Hari Ini</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon amber">⚠️</div>
        <div>
            <div class="stat-value">{{ $alphaHariIni }}</div>
            <div class="stat-label">Alpha Hari Ini</div>
        </div>
    </div>
</div>

{{-- ===== TABEL ABSENSI TERBARU ===== --}}
<div class="card" style="margin-top: 1.25rem;">
    <div class="card-header">
        <span class="card-title">📋 Absensi Terbaru</span>
        <span style="font-size:0.75rem;color:var(--gray-500);">
            Hari ini, {{ \Carbon\Carbon::today()->translatedFormat('d F Y') }}
        </span>
    </div>
    <div class="card-body" style="padding:0;">
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nama</th>
                        <th>Kelas / Jabatan</th>
                        <th>Tipe</th>
                        <th>Jam Masuk</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($absensiTerbaru as $index => $absen)
                    <tr>
                        <td style="font-weight:700;color:var(--gray-500);">{{ $index + 1 }}</td>
                        <td>
                            <div style="display:flex;align-items:center;gap:8px;">
                                <div style="width:30px;height:30px;border-radius:50%;background:linear-gradient(135deg,var(--red),var(--red-light));display:flex;align-items:center;justify-content:center;color:white;font-size:11px;font-weight:800;flex-shrink:0;">
                                    {{ strtoupper(substr($absen['nama'], 0, 2)) }}
                                </div>
                                <span style="font-weight:600;font-size:0.85rem;color:var(--gray-900);">
                                    {{ $absen['nama'] }}
                                </span>
                            </div>
                        </td>
                        <td style="font-size:0.82rem;color:var(--gray-600);">{{ $absen['kelas_jabatan'] }}</td>
                        <td>
                            <span style="font-size:0.78rem;font-weight:600;color:var(--gray-700);">
                                {{ $absen['tipe'] }}
                            </span>
                        </td>
                        <td style="font-weight:600;font-size:0.85rem;">{{ $absen['jam_masuk'] ?? '-' }}</td>
                        <td>
                            @php
                                $badgeClass = match($absen['status']) {
                                    'hadir' => 'badge-hadir',
                                    'sakit' => 'badge-sakit',
                                    'izin'  => 'badge-izin',
                                    'alpha' => 'badge-alpha',
                                    default => 'badge-hadir',
                                };
                                $statusLabel = match($absen['status']) {
                                    'hadir' => 'Hadir',
                                    'sakit' => 'Sakit',
                                    'izin'  => 'Izin',
                                    'alpha' => 'Alpha',
                                    default => ucfirst($absen['status']),
                                };
                            @endphp
                            <span class="badge {{ $badgeClass }}">{{ $statusLabel }}</span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" style="text-align:center;padding:2rem;color:var(--gray-500);font-size:0.85rem;">
                            📭 Belum ada data absensi hari ini
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- ===== QUICK LINKS ===== --}}
<div class="dash-grid" style="margin-top:1.25rem;">
    {{-- Ringkasan Status Hari Ini --}}
    <div class="card">
        <div class="card-header">
            <span class="card-title">📊 Ringkasan Hari Ini</span>
            <span style="font-size:0.75rem;color:var(--gray-500);">
                {{ \Carbon\Carbon::today()->translatedFormat('d F Y') }}
            </span>
        </div>
        <div class="card-body">
            <div style="display:flex;flex-direction:column;gap:0.75rem;">
                <div style="display:flex;align-items:center;justify-content:space-between;padding:0.65rem 1rem;background:var(--gray-50);border-radius:10px;border:1px solid var(--gray-200);">
                    <div style="display:flex;align-items:center;gap:8px;font-size:0.85rem;font-weight:600;color:var(--gray-700);">
                        <span>✅</span> Hadir
                    </div>
                    <span class="badge badge-hadir" style="font-size:0.82rem;">{{ $hadirHariIni }}</span>
                </div>
                <div style="display:flex;align-items:center;justify-content:space-between;padding:0.65rem 1rem;background:var(--gray-50);border-radius:10px;border:1px solid var(--gray-200);">
                    <div style="display:flex;align-items:center;gap:8px;font-size:0.85rem;font-weight:600;color:var(--gray-700);">
                        <span>🏥</span> Sakit
                    </div>
                    <span class="badge badge-sakit" style="font-size:0.82rem;">{{ $sakitHariIni }}</span>
                </div>
                <div style="display:flex;align-items:center;justify-content:space-between;padding:0.65rem 1rem;background:var(--gray-50);border-radius:10px;border:1px solid var(--gray-200);">
                    <div style="display:flex;align-items:center;gap:8px;font-size:0.85rem;font-weight:600;color:var(--gray-700);">
                        <span>📝</span> Izin
                    </div>
                    <span class="badge badge-izin" style="font-size:0.82rem;">{{ $izinHariIni }}</span>
                </div>
                <div style="display:flex;align-items:center;justify-content:space-between;padding:0.65rem 1rem;background:var(--gray-50);border-radius:10px;border:1px solid var(--gray-200);">
                    <div style="display:flex;align-items:center;gap:8px;font-size:0.85rem;font-weight:600;color:var(--gray-700);">
                        <span>❌</span> Alpha
                    </div>
                    <span class="badge badge-alpha" style="font-size:0.82rem;">{{ $alphaHariIni }}</span>
                </div>
            </div>
        </div>
    </div>

</div>

@endsection