@extends('layouts.app')

@section('title', 'Dashboard Kepala Sekolah')

@section('content')

{{-- ===== STAT CARDS ===== --}}
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon red">
            <img src="https://img.icons8.com/color/48/teacher.png" width="32" height="32" alt="guru">
        </div>
        <div>
            <div class="stat-value">{{ $stats['total_guru'] }}</div>
            <div class="stat-label">Total Guru</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon blue">
            <img src="https://img.icons8.com/color/48/student-male.png" width="32" height="32" alt="siswa">
        </div>
        <div>
            <div class="stat-value">{{ $stats['total_siswa'] }}</div>
            <div class="stat-label">Total Siswa</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon green">
            <img src="https://img.icons8.com/color/48/checked-checkbox.png" width="32" height="32" alt="hadir">
        </div>
        <div>
            <div class="stat-value">{{ $stats['guru_hadir'] }}</div>
            <div class="stat-label">Guru Hadir Hari Ini</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon amber">
            <img src="https://img.icons8.com/color/48/error--v1.png" width="32" height="32" alt="alpha">
        </div>
        <div>
            <div class="stat-value">{{ $stats['guru_alpha'] }}</div>
            <div class="stat-label">Guru Alpha Hari Ini</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon green">
            <img src="https://img.icons8.com/color/48/checked-checkbox.png" width="32" height="32" alt="hadir">
        </div>
        <div>
            <div class="stat-value">{{ $stats['siswa_hadir'] }}</div>
            <div class="stat-label">Siswa Hadir Hari Ini</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon red">
            <img src="https://img.icons8.com/color/48/multiply.png" width="32" height="32" alt="alpha">
        </div>
        <div>
            <div class="stat-value">{{ $stats['siswa_alpha'] }}</div>
            <div class="stat-label">Siswa Alpha Hari Ini</div>
        </div>
    </div>
</div>

{{-- ===== REKAP BULAN INI ===== --}}
<div class="dash-grid" style="margin-top:1.5rem;">

    {{-- Rekap Guru --}}
    <div class="card">
        <div class="card-header">
            <span class="card-title">📊 Rekap Guru Bulan Ini</span>
            <span style="font-size:0.75rem;color:var(--gray-500);">{{ \Carbon\Carbon::today()->translatedFormat('F Y') }}</span>
        </div>
        <div class="card-body">
            <div style="display:flex;flex-direction:column;gap:0.6rem;">
                @php $totalG = array_sum($rekapGuru); @endphp
                @foreach(['hadir'=>['#2E7D32','checked-checkbox'],'sakit'=>['#1565C0','heart-with-pulse'],'izin'=>['#F57F17','document'],'alpha'=>['#C0392B','multiply']] as $key=>[$color,$icon])
                <div style="display:flex;align-items:center;justify-content:space-between;padding:0.7rem 1rem;background:var(--gray-50);border-radius:10px;border:1px solid var(--gray-200);">
                    <div style="display:flex;align-items:center;gap:10px;font-size:0.85rem;font-weight:600;color:var(--gray-700);">
                        <img src="https://img.icons8.com/color/32/{{ $icon }}.png" width="20" height="20"> {{ ucfirst($key) }}
                    </div>
                    <span style="font-weight:800;font-size:0.9rem;color:{{ $color }};">{{ $rekapGuru[$key] }}</span>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Rekap Siswa --}}
    <div class="card">
        <div class="card-header">
            <span class="card-title">📊 Rekap Siswa Bulan Ini</span>
            <span style="font-size:0.75rem;color:var(--gray-500);">{{ \Carbon\Carbon::today()->translatedFormat('F Y') }}</span>
        </div>
        <div class="card-body">
            <div style="display:flex;flex-direction:column;gap:0.6rem;">
                @php $totalS = array_sum($rekapSiswa); @endphp
                @foreach(['hadir'=>['#2E7D32','checked-checkbox'],'sakit'=>['#1565C0','heart-with-pulse'],'izin'=>['#F57F17','document'],'alpha'=>['#C0392B','multiply']] as $key=>[$color,$icon])
                <div style="display:flex;align-items:center;justify-content:space-between;padding:0.7rem 1rem;background:var(--gray-50);border-radius:10px;border:1px solid var(--gray-200);">
                    <div style="display:flex;align-items:center;gap:10px;font-size:0.85rem;font-weight:600;color:var(--gray-700);">
                        <img src="https://img.icons8.com/color/32/{{ $icon }}.png" width="20" height="20"> {{ ucfirst($key) }}
                    </div>
                    <span style="font-weight:800;font-size:0.9rem;color:{{ $color }};">{{ $rekapSiswa[$key] }}</span>
                </div>
                @endforeach
            </div>
        </div>
    </div>

</div>

{{-- ===== CHART TREN ===== --}}
<div class="card" style="margin-top:1.5rem;">
    <div class="card-header">
        <span class="card-title">📈 Tren Kehadiran 6 Bulan Terakhir</span>
    </div>
    <div class="card-body">
        <canvas id="trendChart" height="80"></canvas>
    </div>
</div>

{{-- ===== GURU ALPHA TERBANYAK ===== --}}
<div class="card" style="margin-top:1.5rem;">
    <div class="card-header">
        <span class="card-title">⚠️ Guru Alpha Terbanyak Bulan Ini</span>
        <a href="{{ route('kepala-sekolah.rekap.index') }}"
           style="font-size:0.78rem;color:var(--red);font-weight:600;text-decoration:none;">
            Lihat Semua →
        </a>
    </div>
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nama Guru</th>
                    <th>Jumlah Alpha</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($guruTerendah as $i => $guru)
                <tr>
                    <td style="color:var(--gray-500);font-weight:600;">{{ $i + 1 }}</td>
                    <td>
                        <div style="display:flex;align-items:center;gap:8px;">
                            <div style="width:30px;height:30px;border-radius:50%;background:linear-gradient(135deg,var(--red),var(--red-light));display:flex;align-items:center;justify-content:center;color:white;font-size:11px;font-weight:800;flex-shrink:0;">
                                {{ strtoupper(substr($guru->nama, 0, 2)) }}
                            </div>
                            <span style="font-weight:600;font-size:0.85rem;">{{ $guru->nama }}</span>
                        </div>
                    </td>
                    <td>
                        <span style="font-weight:700;color:{{ $guru->alpha_count > 3 ? '#e74c3c' : '#f39c12' }};">
                            {{ $guru->alpha_count }}x
                        </span>
                    </td>
                    <td>
                        @if($guru->alpha_count === 0)
                            <span class="badge badge-hadir">Baik</span>
                        @elseif($guru->alpha_count <= 2)
                            <span class="badge badge-izin">Perlu Perhatian</span>
                        @else
                            <span class="badge badge-alpha">Kritis</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" style="text-align:center;padding:2.5rem;color:var(--gray-500);font-size:0.85rem;">
                        🖨️ Tidak ada data alpha bulan ini
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const ctx = document.getElementById('trendChart').getContext('2d');
new Chart(ctx, {
    type: 'line',
    data: {
        labels: @json($chartLabels),
        datasets: [
            {
                label: 'Kehadiran Guru',
                data: @json($chartDataGuru),
                borderColor: '#C0392B',
                backgroundColor: 'rgba(192,57,43,0.08)',
                tension: 0.4, fill: true,
                pointBackgroundColor: '#C0392B', pointRadius: 4,
            },
            {
                label: 'Kehadiran Siswa',
                data: @json($chartDataSiswa),
                borderColor: '#3498db',
                backgroundColor: 'rgba(52,152,219,0.08)',
                tension: 0.4, fill: true,
                pointBackgroundColor: '#3498db', pointRadius: 4,
            }
        ]
    },
    options: {
        responsive: true,
        plugins: { legend: { position: 'top' } },
        scales: {
            y: { beginAtZero: true, grid: { color: '#f5f5f5' } },
            x: { grid: { display: false } }
        }
    }
});
</script>
@endpush