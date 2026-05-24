@extends('layouts.app')

@section('title', 'Rekap Absensi')

@push('styles')
<style>
    .stat-card-rekap {
        background: white; border-radius: var(--radius-lg);
        padding: 1.25rem 1.5rem; box-shadow: var(--shadow-sm);
        border: 1px solid var(--gray-200);
        display: flex; align-items: center; gap: 1.25rem;
    }
    .stat-icon-rekap {
        width: 56px; height: 56px; border-radius: 16px;
        display: flex; align-items: center; justify-content: center; font-size: 26px; flex-shrink: 0;
    }
    .stat-pct { font-size: 2rem; font-weight: 800; color: var(--gray-900); line-height: 1; }
    .stat-lbl { font-size: 0.75rem; color: var(--gray-500); margin-top: 3px; }
    .pct-badge {
        display: inline-block; padding: 3px 12px; border-radius: 20px; font-size: 0.75rem; font-weight: 700;
    }
    .pct-high  { background: #E8F5E9; color: #2E7D32; }
    .pct-mid   { background: #FFF8E1; color: #F57F17; }
    .pct-low   { background: var(--red-pale); color: var(--red); }
    .chart-container { position: relative; height: 260px; }
</style>
@endpush

@section('content')

{{-- TOP CARD: JUDUL + FILTER --}}
<div class="card" style="margin-bottom:1.25rem;">
    <div class="card-header" style="flex-wrap:wrap;gap:0.75rem;">
        <span class="card-title">📊 Rekap Absensi</span>
        <div style="display:flex;gap:0.5rem;align-items:center;flex-wrap:wrap;">
            <form method="GET" action="{{ route('kepala-sekolah.rekap.index') }}" id="filterForm"
                style="display:flex;gap:0.5rem;align-items:center;flex-wrap:wrap;">
                <select name="tipe" onchange="document.getElementById('filterForm').submit()"
                    style="padding:0.5rem 0.75rem;border:1.5px solid var(--gray-200);border-radius:10px;font-family:inherit;font-size:0.83rem;outline:none;">
                    <option value="siswa" {{ request('tipe','siswa')=='siswa'?'selected':'' }}>Siswa</option>
                    <option value="guru"  {{ request('tipe')=='guru'?'selected':'' }}>Guru</option>
                </select>
                <select name="bulan" onchange="document.getElementById('filterForm').submit()"
                    style="padding:0.5rem 0.75rem;border:1.5px solid var(--gray-200);border-radius:10px;font-family:inherit;font-size:0.83rem;outline:none;">
                    @foreach($bulanList as $n => $nm)
                        <option value="{{ $n }}" {{ $bulan == $n ? 'selected' : '' }}>{{ $nm }}</option>
                    @endforeach
                </select>
                <select name="tahun" onchange="document.getElementById('filterForm').submit()"
                    style="padding:0.5rem 0.75rem;border:1.5px solid var(--gray-200);border-radius:10px;font-family:inherit;font-size:0.83rem;outline:none;">
                    @foreach($tahunList as $y)
                        <option value="{{ $y }}" {{ $tahun == $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endforeach
                </select>
            </form>
            <button onclick="window.print()" class="btn btn-primary" style="padding:0.6rem 1.5rem;font-size:0.88rem;">
                🖨️ Cetak
            </button>
        </div>
    </div>
</div>

{{-- STAT CARDS --}}
<div style="display:grid;grid-template-columns:repeat(4,1fr);gap:1rem;margin-bottom:1.25rem;">
    <div class="stat-card-rekap">
        <div class="stat-icon-rekap" style="background:#E8F5E9;">✅</div>
        <div>
            <div class="stat-pct" style="color:#2E7D32;">{{ $rataHadir }}%</div>
            <div class="stat-lbl">Rata-rata Hadir</div>
        </div>
    </div>
    <div class="stat-card-rekap">
        <div class="stat-icon-rekap" style="background:#E3F2FD;">🏥</div>
        <div>
            <div class="stat-pct" style="color:#1565C0;">{{ $rataSakit }}%</div>
            <div class="stat-lbl">Rata-rata Sakit</div>
        </div>
    </div>
    <div class="stat-card-rekap">
        <div class="stat-icon-rekap" style="background:#FFF8E1;">📝</div>
        <div>
            <div class="stat-pct" style="color:#F57F17;">{{ $rataIzin }}%</div>
            <div class="stat-lbl">Rata-rata Izin</div>
        </div>
    </div>
    <div class="stat-card-rekap">
        <div class="stat-icon-rekap" style="background:var(--red-pale);">❌</div>
        <div>
            <div class="stat-pct" style="color:var(--red);">{{ $rataAlpha }}%</div>
            <div class="stat-lbl">Rata-rata Alpha</div>
        </div>
    </div>
</div>

{{-- CHART + TERENDAH --}}
<div style="display:grid;grid-template-columns:1fr 1fr;gap:1.25rem;margin-bottom:1.25rem;">

    {{-- CHART --}}
    <div class="card">
        <div class="card-header">
            <span class="card-title">📈 Tren Kehadiran Bulanan</span>
            <span style="font-size:0.75rem;color:var(--gray-500);">{{ date('Y') }}</span>
        </div>
        <div class="card-body">
            <div class="chart-container">
                <canvas id="trendChart"></canvas>
            </div>
        </div>
    </div>

    {{-- TERENDAH --}}
    <div class="card">
        <div class="card-header">
            <span class="card-title">🥉 {{ request('tipe','siswa') == 'guru' ? 'Guru' : 'Siswa' }} Terendah Kehadiran</span>
        </div>
        <div class="card-body" style="padding:0;">
            <table>
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>{{ request('tipe') == 'guru' ? 'Mapel' : 'Kelas' }}</th>
                        <th>% Hadir</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($terendah as $item)
                    <tr>
                        <td style="font-weight:600;font-size:0.85rem;">{{ $item['nama'] }}</td>
                        <td style="font-size:0.82rem;color:var(--gray-600);">{{ $item['kelas_jabatan'] }}</td>
                        <td>
                            @php
                                $pct = $item['pct_hadir'];
                                $cls = $pct >= 80 ? 'pct-mid' : 'pct-low';
                            @endphp
                            <span class="pct-badge {{ $cls }}">{{ $pct }}%</span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" style="text-align:center;padding:1.5rem;color:var(--gray-500);font-size:0.83rem;">
                            Tidak ada data
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- DETAIL REKAP TABLE --}}
<div class="card">
    <div class="card-header">
        <span class="card-title">📋 Detail Rekap Per {{ request('tipe','siswa') == 'guru' ? 'Guru' : 'Siswa' }}</span>
        <span style="font-size:0.78rem;color:var(--gray-500);">
            {{ $bulanList[$bulan] ?? '' }} {{ $tahun }}
        </span>
    </div>
    <div class="card-body" style="padding:0;">
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th style="width:40px;">#</th>
                        <th>Nama</th>
                        <th>{{ request('tipe','siswa') == 'guru' ? 'Mata Pelajaran' : 'Kelas' }}</th>
                        <th style="text-align:center;">Hadir</th>
                        <th style="text-align:center;">Sakit</th>
                        <th style="text-align:center;">Izin</th>
                        <th style="text-align:center;">Alpha</th>
                        <th style="text-align:center;">% Hadir</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($rekapDetail as $i => $row)
                    <tr>
                        <td style="font-weight:700;color:var(--gray-500);">{{ $i + 1 }}</td>
                        <td>
                            <div style="display:flex;align-items:center;gap:8px;">
                                <div style="width:30px;height:30px;border-radius:50%;background:linear-gradient(135deg,var(--red),var(--red-light));display:flex;align-items:center;justify-content:center;color:white;font-size:11px;font-weight:800;flex-shrink:0;">
                                    {{ strtoupper(substr($row['nama'],0,2)) }}
                                </div>
                                <span style="font-weight:600;font-size:0.85rem;color:var(--gray-900);">{{ $row['nama'] }}</span>
                            </div>
                        </td>
                        <td style="font-size:0.82rem;color:var(--gray-600);">{{ $row['kelas_jabatan'] }}</td>
                        <td style="text-align:center;font-weight:600;">{{ $row['hadir'] }}</td>
                        <td style="text-align:center;color:#1565C0;font-weight:600;">{{ $row['sakit'] }}</td>
                        <td style="text-align:center;color:#F57F17;font-weight:600;">{{ $row['izin'] }}</td>
                        <td style="text-align:center;color:var(--red);font-weight:600;">{{ $row['alpha'] }}</td>
                        <td style="text-align:center;">
                            @php
                                $p = $row['pct_hadir'];
                                $cls = $p >= 90 ? 'pct-high' : ($p >= 75 ? 'pct-mid' : 'pct-low');
                            @endphp
                            <span class="pct-badge {{ $cls }}">{{ $p }}%</span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" style="text-align:center;padding:2.5rem;color:var(--gray-500);font-size:0.85rem;">
                            Belum ada data absensi untuk periode ini
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
const trendData = @json($trendBulanan);

const ctx = document.getElementById('trendChart').getContext('2d');
new Chart(ctx, {
    type: 'line',
    data: {
        labels: trendData.map(d => d.bulan),
        datasets: [{
            label: '% Kehadiran',
            data: trendData.map(d => d.pct),
            borderColor: '#C0392B',
            backgroundColor: 'rgba(192,57,43,0.08)',
            borderWidth: 2.5,
            pointBackgroundColor: '#C0392B',
            pointRadius: 4,
            pointHoverRadius: 6,
            tension: 0.4,
            fill: true,
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: { display: false },
            tooltip: {
                callbacks: {
                    label: ctx => ctx.parsed.y + '%'
                }
            }
        },
        scales: {
            y: {
                min: 0, max: 100,
                ticks: {
                    callback: v => v + '%',
                    font: { size: 11 },
                    color: '#9AA0A6',
                },
                grid: { color: '#F1F3F4' }
            },
            x: {
                ticks: { font: { size: 11 }, color: '#9AA0A6' },
                grid: { display: false }
            }
        }
    }
});
</script>
@endpush
