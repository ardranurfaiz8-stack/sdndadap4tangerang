@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')

{{-- Stats Grid --}}
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon green">✅</div>
        <div>
            <div class="stat-value">{{ $rekapBulan['hadir'] ?? 0 }}</div>
            <div class="stat-label">Hadir Bulan Ini</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon blue">🩺</div>
        <div>
            <div class="stat-value">{{ $rekapBulan['sakit'] ?? 0 }}</div>
            <div class="stat-label">Sakit Bulan Ini</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon amber">📄</div>
        <div>
            <div class="stat-value">{{ $rekapBulan['izin'] ?? 0 }}</div>
            <div class="stat-label">Izin Bulan Ini</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon red">❌</div>
        <div>
            <div class="stat-value">{{ $rekapBulan['alpha'] ?? 0 }}</div>
            <div class="stat-label">Alpha Bulan Ini</div>
        </div>
    </div>
</div>

{{-- Tabel Absensi Terbaru --}}
<div class="card">
    <div class="card-header">
        <span class="card-title">📋 Absensi Terbaru</span>
        <span style="font-size:0.78rem;color:var(--gray-500);">
            Hari ini, {{ \Carbon\Carbon::today()->translatedFormat('j F Y') }}
        </span>
    </div>
    <div class="card-body" style="padding:0;">
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nama</th>
                        <th>Kelas/Jabatan</th>
                        <th>Tipe</th>
                        <th>Jam Masuk</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($absensiTerbaru as $i => $absen)
                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td style="font-weight:600;color:var(--gray-900);">{{ $absen['nama'] }}</td>
                        <td>{{ $absen['kelas'] }}</td>
                        <td>
                            <span style="
                                display:inline-block;padding:2px 8px;border-radius:20px;
                                font-size:0.7rem;font-weight:600;
                                background:{{ $absen['tipe'] === 'Guru' ? '#E3F2FD' : 'var(--red-pale)' }};
                                color:{{ $absen['tipe'] === 'Guru' ? '#1565C0' : 'var(--red)' }};
                            ">
                                {{ $absen['tipe'] }}
                            </span>
                        </td>
                        <td>{{ $absen['jam_masuk'] ? \Carbon\Carbon::parse($absen['jam_masuk'])->format('H:i') : '-' }}</td>
                        <td>
                            @php $s = strtolower($absen['status']); @endphp
                            <span class="badge badge-{{ $s }}">{{ ucfirst($s) }}</span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" style="text-align:center;padding:2.5rem;color:var(--gray-500);">
                            <div style="font-size:2rem;margin-bottom:0.5rem;">📭</div>
                            <div style="font-weight:600;">Belum ada data absensi hari ini</div>
                            <div style="font-size:0.78rem;margin-top:4px;">Data akan muncul setelah absensi dicatat</div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection