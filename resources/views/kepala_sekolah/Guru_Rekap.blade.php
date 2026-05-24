@extends('layouts.app')

@section('title', 'Rekap Absensi Saya')

@section('content')
<div class="page-header">
    <h1 class="page-title">Rekap Absensi Saya</h1>
    <p class="page-subtitle">Ringkasan kehadiran {{ $guru->nama }}</p>
</div>

{{-- Filter --}}
<div class="card" style="margin-bottom: 24px;">
    <div class="card-body">
        <form method="GET" action="{{ route('guru.rekap.index') }}" style="display: flex; gap: 16px; flex-wrap: wrap; align-items: flex-end;">
            <div class="form-group" style="margin-bottom: 0; flex: 1; min-width: 140px;">
                <label class="form-label">Bulan</label>
                <select name="bulan" class="form-control">
                    @foreach($bulanList as $num => $nama)
                        <option value="{{ $num }}" {{ $bulan == $num ? 'selected' : '' }}>{{ $nama }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group" style="margin-bottom: 0; flex: 1; min-width: 120px;">
                <label class="form-label">Tahun</label>
                <select name="tahun" class="form-control">
                    @foreach($tahunList as $y)
                        <option value="{{ $y }}" {{ $tahun == $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endforeach
                </select>
            </div>
            <div style="margin-bottom: 0;">
                <button type="submit" class="btn btn-primary">Tampilkan</button>
            </div>
        </form>
    </div>
</div>

{{-- Stats --}}
<div class="stats-grid" style="margin-bottom: 24px;">
    @foreach(['hadir'=>['#2ecc71','✓','Hadir'],'sakit'=>['#f39c12','🤒','Sakit'],'izin'=>['#3498db','📋','Izin'],'alpha'=>['#e74c3c','✗','Alpha']] as $key => [$color, $icon, $label])
    <div class="stat-card" style="border-top: 3px solid {{ $color }};">
        <div class="stat-number" style="color: {{ $color }};">{{ $rekap[$key] }}</div>
        <div class="stat-label">{{ $label }}</div>
    </div>
    @endforeach
</div>

{{-- Persentase Kehadiran --}}
@php
    $persen = $rekap['total'] > 0 ? round(($rekap['hadir'] / $rekap['total']) * 100) : 0;
@endphp
<div class="card" style="margin-bottom: 24px;">
    <div class="card-header"><h3 class="card-title">Persentase Kehadiran Bulan Ini</h3></div>
    <div class="card-body">
        <div style="display: flex; align-items: center; gap: 24px;">
            <div style="width: 90px; height: 90px; border-radius: 50%; background: conic-gradient(#C0392B {{ $persen }}%, #f0f0f0 0); display: flex; align-items: center; justify-content: center; position: relative;">
                <div style="width: 70px; height: 70px; border-radius: 50%; background: white; display: flex; align-items: center; justify-content: center; font-size: 18px; font-weight: 700; color: #C0392B;">
                    {{ $persen }}%
                </div>
            </div>
            <div>
                <p style="margin: 0; font-size: 14px; color: #666;">Total hari tercatat: <strong>{{ $rekap['total'] }}</strong></p>
                <p style="margin: 4px 0 0; font-size: 14px; color: #666;">Hadir: <strong style="color: #2ecc71;">{{ $rekap['hadir'] }} hari</strong></p>
                <p style="margin: 4px 0 0; font-size: 14px; color: #666;">Tidak hadir: <strong style="color: #e74c3c;">{{ $rekap['sakit'] + $rekap['izin'] + $rekap['alpha'] }} hari</strong></p>
            </div>
        </div>
    </div>
</div>

{{-- Chart 6 bulan --}}
<div class="card" style="margin-bottom: 24px;">
    <div class="card-header"><h3 class="card-title">Tren Kehadiran 6 Bulan Terakhir</h3></div>
    <div class="card-body">
        <canvas id="rekapChart" height="80"></canvas>
    </div>
</div>

{{-- Tabel Detail --}}
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Detail Absensi — {{ $bulanList[$bulan] ?? '' }} {{ $tahun }}</h3>
    </div>
    <div class="card-body" style="padding: 0;">
        <div class="table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Tanggal</th>
                        <th>Hari</th>
                        <th>Jam Masuk</th>
                        <th>Status</th>
                        <th>Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($absenList as $i => $absen)
                        @php
                            $colors = ['hadir'=>'#2ecc71','sakit'=>'#f39c12','izin'=>'#3498db','alpha'=>'#e74c3c'];
                            $c = $colors[$absen->status] ?? '#999';
                        @endphp
                        <tr>
                            <td>{{ $i + 1 }}</td>
                            <td>{{ \Carbon\Carbon::parse($absen->tanggal)->format('d/m/Y') }}</td>
                            <td>{{ \Carbon\Carbon::parse($absen->tanggal)->translatedFormat('l') }}</td>
                            <td>{{ $absen->jam_masuk ?? '-' }}</td>
                            <td>
                                <span class="status-badge" style="background: {{ $c }}20; color: {{ $c }}; border: 1px solid {{ $c }}40;">
                                    {{ ucfirst($absen->status) }}
                                </span>
                            </td>
                            <td>{{ $absen->keterangan ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted" style="padding: 40px;">
                                Tidak ada data absensi pada bulan ini.
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
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const ctx = document.getElementById('rekapChart').getContext('2d');
new Chart(ctx, {
    type: 'bar',
    data: {
        labels: @json($chartLabels),
        datasets: [{
            label: 'Hari Hadir',
            data: @json($chartData),
            backgroundColor: 'rgba(192,57,43,0.7)',
            borderColor: '#C0392B',
            borderWidth: 2,
            borderRadius: 6,
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: {
            y: { beginAtZero: true, ticks: { stepSize: 1 }, grid: { color: '#f0f0f0' } },
            x: { grid: { display: false } }
        }
    }
});
</script>
@endpush