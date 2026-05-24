@extends('guru.layout')
@section('title','Absensi Guru')
@section('page-title','Absensi Guru')

@section('content')

<div class="card">
  <div class="card-hd">
    <div class="card-title">🪪 QR Code Identitas Guru</div>
    <div class="flex gap2" style="align-items:center;">
      <select class="fc" style="width:200px;" id="sel-guru-qr" onchange="gantiQR(this.value)">
        @foreach($guruList ?? [] as $g)
        <option value="{{ $g->id ?? '' }}"
                data-nama="{{ $g->nama ?? '' }}"
                data-mapel="{{ $g->mata_pelajaran ?? '' }}"
                data-nip="{{ $g->nip ?? '' }}"
                {{ (($selectedGuru->id ?? null) == ($g->id ?? null)) ? 'selected':'' }}>
          {{ $g->nama ?? '' }}
        </option>
        @endforeach
      </select>
      <button class="btn btn-s no-print" onclick="cetakQR()">🖨️ Cetak QR</button>
    </div>
  </div>

  <div class="card-body">
    <div class="g2" style="gap:24px;align-items:start;">
      <div style="display:flex;flex-direction:column;align-items:center;gap:14px;">
        <div id="qr-card-wrap" style="background:#fff;border:1.5px solid var(--g200);border-radius:12px;padding:20px 24px;text-align:center;max-width:280px;width:100%;box-shadow:var(--sh);">
          <div style="background:var(--red);color:#fff;font-size:11px;font-weight:800;padding:4px 12px;border-radius:4px;margin-bottom:14px;display:inline-block;letter-spacing:.5px;">
            SDN DADAP 4 — QR ABSENSI GURU
          </div>
          <div style="display:flex;align-items:center;gap:10px;margin-bottom:14px;text-align:left;">
            <div id="qr-av" class="qr-av" style="width:48px;height:48px;font-size:18px;background:var(--red);">
              {{ strtoupper(substr($selectedGuru->nama ?? ($guruList->first()->nama ?? 'G'), 0, 2)) }}
            </div>
            <div>
              <div id="qr-nama" style="font-size:14px;font-weight:800;color:var(--g900);">
                {{ $selectedGuru->nama ?? ($guruList->first()->nama ?? 'Nama Guru') }}
              </div>
              <div id="qr-mapel" style="font-size:12px;color:var(--g500);">
                {{ $selectedGuru->mata_pelajaran ?? ($guruList->first()->mata_pelajaran ?? 'Mata Pelajaran') }}
              </div>
              <div id="qr-nip-label" style="font-size:11px;color:var(--g400);font-family:monospace;">
                NIP: {{ $selectedGuru->nip ?? ($guruList->first()->nip ?? '-') }}
              </div>
            </div>
          </div>
          <div style="display:flex;justify-content:center;">
            {{-- ✅ PERBAIKAN 1: QR utama pakai URL scan --}}
            <img id="qr-img"
                 src="https://api.qrserver.com/v1/create-qr-code/?size=160x160&data={{ urlencode(route('absen.scan', $selectedGuru->nip ?? ($guruList->first()->nip ?? 'GURU001'))) }}"
                 width="160" height="160" alt="QR Code" style="border-radius:4px;">
          </div>
          <div id="qr-nip-bawah" style="font-size:11px;color:var(--g400);font-family:monospace;margin-top:8px;letter-spacing:.5px;">
            {{ $selectedGuru->nip ?? ($guruList->first()->nip ?? '-') }}
          </div>
        </div>
      </div>

      <div>
        <div style="margin-top:14px;">
          <div style="font-size:12.5px;font-weight:700;color:var(--g700);margin-bottom:8px;">
            📋 Log Scan Hari Ini
          </div>
          <div id="scan-log">
            @if(count($logScanHariIni ?? []) === 0)
              <div style="font-size:13px;color:var(--g400);text-align:center;padding:14px 0;">
                Belum ada scan hari ini
              </div>
            @else
              @foreach($logScanHariIni as $log)
              <div style="display:flex;justify-content:space-between;align-items:center;padding:6px 0;border-bottom:1px solid var(--g100);font-size:12.5px;">
                <span class="fw7">{{ $log->guru->nama ?? '-' }}</span>
                <span class="tm">{{ $log->jam_masuk ?? '-' }}</span>
                <span class="badge bg-g">Hadir</span>
              </div>
              @endforeach
            @endif
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

{{-- SEMUA QR CODE GURU --}}
<div class="card">
  <div class="card-hd">
    <div class="card-title">🪪 Semua QR Code Guru</div>
  </div>
  <div class="card-body">
    <div class="qr-grid">
      @forelse($guruList ?? [] as $g)
      @php
        $inisial = strtoupper(collect(explode(' ', $g->nama ?? 'G'))->take(2)->map(fn($w)=>$w[0])->join(''));
        $colors  = ['#e53935','#1e88e5','#43a047','#f57c00','#8e24aa','#00897b'];
        $warna   = $colors[$loop->index % count($colors)];
      @endphp
      <div class="qr-card">
        <div class="qr-av" style="background:{{ $warna }};">{{ $inisial }}</div>
        <div class="qr-name">{{ $g->nama ?? '-' }}</div>
        <div class="qr-sub">{{ $g->mata_pelajaran ?? '-' }}</div>
        <div style="display:flex;justify-content:center;">
          {{-- ✅ PERBAIKAN 2: Grid QR pakai $g->nip masing-masing guru --}}
          <img src="https://api.qrserver.com/v1/create-qr-code/?size=100x100&data={{ urlencode(route('absen.scan', $g->nip ?? $g->id)) }}"
               width="100" height="100" alt="QR" style="border-radius:4px;" loading="lazy">
        </div>
        <div class="qr-nip">{{ $g->nip ?? '-' }}</div>
      </div>
      @empty
      <div style="grid-column:span 4;text-align:center;padding:24px;color:var(--g400);">
        Belum ada data guru
      </div>
      @endforelse
    </div>
  </div>
</div>

{{-- DAFTAR ABSENSI GURU --}}
<div class="card">
  <div class="card-hd">
    <div class="card-title">📋 Daftar Absensi Guru</div>
    <div class="flex gap2" style="align-items:center;flex-wrap:wrap;">
      <input type="date" class="fc" style="width:150px;" id="filter-tgl"
             value="{{ request('tanggal', date('Y-m-d')) }}"
             onchange="applyFilter()">
      <div style="position:relative;">
        <input type="text" class="fc" style="width:180px;padding-left:30px;"
               placeholder="Cari nama..." id="cari-nama" oninput="cariNama(this.value)">
        <span style="position:absolute;left:9px;top:50%;transform:translateY(-50%);font-size:13px;color:var(--g400);">🔍</span>
      </div>
      <button class="btn btn-s no-print" onclick="applyFilter()">🔍 Filter</button>
      <a href="{{ route('guru.absen_guru.create') }}" class="btn btn-r btn-sm no-print">+ Tambah</a>
    </div>
  </div>

  <div class="tw">
    <table id="tbl-absen">
      <thead>
        <tr>
          <th>#</th>
          <th>Nama Guru</th>
          <th>Mata Pelajaran</th>
          <th>NIP</th>
          <th>Jam Masuk</th>
          <th>Status</th>
          <th>Keterangan</th>
          <th class="no-print">Aksi</th>
        </tr>
      </thead>
      <tbody>
        @forelse($absensi ?? [] as $i => $ab)
        @php
          $inisial = strtoupper(collect(explode(' ', $ab->guru->nama ?? 'G'))->take(2)->map(fn($w)=>$w[0])->join(''));
          $colors  = ['#e53935','#1e88e5','#43a047','#f57c00','#8e24aa','#00897b'];
          $warna   = $colors[$i % count($colors)];
        @endphp
        <tr>
          <td class="tm">{{ $i + 1 }}</td>
          <td>
            <div class="flex gap2" style="align-items:center;">
              <div class="av" style="background:{{ $warna }};color:#fff;">{{ $inisial }}</div>
              <span class="fw7">{{ $ab->guru->nama ?? '-' }}</span>
            </div>
          </td>
          <td>{{ $ab->guru->mata_pelajaran ?? '-' }}</td>
          <td class="tm" style="font-family:monospace;font-size:11px;">{{ $ab->guru->nip ?? '-' }}</td>
          <td class="fw7">{{ $ab->jam_masuk ?? '–' }}</td>
          <td>
            @if($ab->status === 'hadir')     <span class="badge bg-g">Hadir</span>
            @elseif($ab->status === 'izin')  <span class="badge bg-y">Izin</span>
            @elseif($ab->status === 'sakit') <span class="badge bg-b">Sakit</span>
            @else                            <span class="badge bg-r">Alpha</span>
            @endif
          </td>
          <td class="tm">{{ $ab->keterangan ?? '–' }}</td>
          <td class="no-print">
            <div class="flex gap2">
              <a href="{{ route('guru.absen_guru.edit', $ab->id) }}" class="btn btn-s btn-sm">✏️ Edit</a>
              <form method="POST" action="{{ route('guru.absen_guru.destroy', $ab->id) }}"
                    onsubmit="return confirm('Yakin hapus absensi ini?')" style="display:inline;">
                @csrf @method('DELETE')
                <button class="btn btn-sm" style="background:#FFEBEE;color:#c0392b;border:none;">🗑️</button>
              </form>
            </div>
          </td>
        </tr>
        @empty
        <tr><td colspan="8">
          <div class="empty"><div class="ei">🧑‍🏫</div>
          <p>Belum ada data absensi{{ request('tanggal') ? ' pada tanggal ini' : '' }}</p></div>
        </td></tr>
        @endforelse
      </tbody>
    </table>
  </div>

  @if(isset($absensi) && method_exists($absensi,'hasPages') && $absensi->hasPages())
  <div style="padding:10px 14px;border-top:1px solid var(--g200);">
    {{ $absensi->withQueryString()->links() }}
  </div>
  @endif
</div>

@push('scripts')
<script>
// Base URL untuk scan QR (dipakai di JavaScript)
const scanBaseUrl = "{{ url('/absen/scan') }}";

function applyFilter(){
  const tgl = document.getElementById('filter-tgl').value;
  window.location.href = '?tanggal=' + tgl;
}

function cariNama(q){
  document.querySelectorAll('#tbl-absen tbody tr').forEach(r=>{
    r.style.display = r.textContent.toLowerCase().includes(q.toLowerCase()) ? '' : 'none';
  });
}

function gantiQR(guruId){
  const sel = document.getElementById('sel-guru-qr');
  const opt = sel.options[sel.selectedIndex];
  const nama  = opt.dataset.nama  || '';
  const mapel = opt.dataset.mapel || '';
  const nip   = opt.dataset.nip   || '';
  const inisial = nama.split(' ').slice(0,2).map(w=>w[0]||'').join('').toUpperCase();

  document.getElementById('qr-nama').textContent      = nama;
  document.getElementById('qr-mapel').textContent     = mapel;
  document.getElementById('qr-nip-label').textContent = 'NIP: ' + nip;
  document.getElementById('qr-nip-bawah').textContent = nip;
  document.getElementById('qr-av').textContent        = inisial;

  {{-- ✅ PERBAIKAN 3: JavaScript gantiQR pakai URL scan, bukan GURU:nip --}}
  const scanUrl = scanBaseUrl + '/' + encodeURIComponent(nip);
  document.getElementById('qr-img').src =
    'https://api.qrserver.com/v1/create-qr-code/?size=160x160&data=' + encodeURIComponent(scanUrl);
}

function cetakQR(){ window.print(); }
</script>
@endpush
@endsection