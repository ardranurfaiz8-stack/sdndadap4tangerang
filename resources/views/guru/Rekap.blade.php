@extends('guru.layout')
@section('title','Rekap Absensi')
@section('page-title','Rekap Absensi')

@section('content')

{{-- Filter --}}
<div class="card">
  <div class="card-hd"><div class="card-title">⚙️ Filter Rekap</div></div>
  <div class="card-body">
    <form method="GET" action="{{ route('guru.rekap.index') }}">
      <div class="flex gap3" style="flex-wrap:wrap;align-items:flex-end;">
        <div class="fg" style="margin:0;flex:1;min-width:120px;"><label class="fl">Tipe</label>
          <select name="tipe" class="fc" id="sel-tipe" onchange="toggleKelas(this.value)">
            <option value="siswa" {{ request('tipe','siswa')==='siswa'?'selected':'' }}>Siswa</option>
            <option value="guru"  {{ request('tipe')==='guru'?'selected':'' }}>Guru</option>
          </select></div>
        <div class="fg" style="margin:0;flex:1;min-width:120px;" id="wrap-kelas"><label class="fl">Kelas</label>
          <select name="kelas" class="fc">
            <option value="">Semua Kelas</option>
            @foreach($kelasList ?? ['1A','1B','2A','2B','3A','3B','4A','4B','5A','5B','6A','6B'] as $k)
            <option value="{{ $k }}" {{ request('kelas')===$k?'selected':'' }}>Kelas {{ $k }}</option>
            @endforeach
          </select></div>
        <div class="fg" style="margin:0;flex:1;min-width:145px;"><label class="fl">Bulan</label>
          <input type="month" name="bulan" class="fc" value="{{ request('bulan', date('Y-m')) }}"></div>
        <div class="flex gap2 no-print">
          <button type="submit" class="btn btn-r">🔍 Tampilkan</button>
          <button type="button" class="btn btn-s" onclick="cetakRekap()">🖨️ Cetak</button>
        </div>
      </div>
    </form>
  </div>
</div>

<div class="stat-grid">
  <div class="stat-card"><div class="si si-g">✅</div><div><div class="sv">{{ $totalHadir ?? 0 }}</div><div class="sl">Total Hadir</div></div></div>
  <div class="stat-card"><div class="si si-y">📄</div><div><div class="sv">{{ $totalIzin ?? 0 }}</div><div class="sl">Total Izin</div></div></div>
  <div class="stat-card"><div class="si si-b">🏥</div><div><div class="sv">{{ $totalSakit ?? 0 }}</div><div class="sl">Total Sakit</div></div></div>
  <div class="stat-card"><div class="si si-r">❌</div><div><div class="sv">{{ $totalAlpha ?? 0 }}</div><div class="sl">Total Alpha</div></div></div>
</div>

<div class="card" id="rekap-area">
  {{-- Kop surat print --}}
  <div id="kop" style="display:none;padding:18px 22px 12px;border-bottom:2px solid var(--red);text-align:center;">
    <div style="font-size:10.5px;color:#666;">PEMERINTAH KABUPATEN TANGERANG</div>
    <div style="font-size:17px;font-weight:800;color:var(--red);">SDN DADAP 4</div>
    <div style="font-size:10.5px;color:#666;">Kabupaten Tangerang</div>
    <div style="margin-top:6px;font-size:13px;font-weight:700;">
      REKAP ABSENSI {{ strtoupper(request('tipe','SISWA')) }}
      @if(request('kelas')) — KELAS {{ request('kelas') }} @endif
      — @php try{ echo strtoupper(\Carbon\Carbon::createFromFormat('Y-m',request('bulan',date('Y-m')))->translatedFormat('F Y')); }catch(\Exception $e){ echo date('F Y'); } @endphp
    </div>
  </div>

  <div class="card-hd">
    <div class="card-title">
      📊 Rekap {{ ucfirst(request('tipe','Siswa')) }}
      @if(request('kelas')) — Kelas {{ request('kelas') }} @endif
      — @php try{ echo \Carbon\Carbon::createFromFormat('Y-m',request('bulan',date('Y-m')))->translatedFormat('F Y'); }catch(\Exception $e){ echo date('F Y'); } @endphp
    </div>
    <div class="card-sub">{{ $jumlahRecord ?? 0 }} data</div>
  </div>

  <div class="tw">
    <table>
      <thead>
        <tr>
          <th>#</th><th>Nama</th>
          @if(request('tipe')==='guru') <th>Jabatan</th>
          @else <th>Kelas</th><th>NIS</th> @endif
          <th style="color:var(--green);">Hadir</th>
          <th style="color:var(--yel);">Izin</th>
          <th style="color:var(--blue);">Sakit</th>
          <th style="color:var(--red);">Alpha</th>
          <th>Total</th><th>%</th>
        </tr>
      </thead>
      <tbody>
        @forelse($rekap ?? [] as $i => $r)
        @php $jml=($r->hadir??0)+($r->izin??0)+($r->sakit??0)+($r->alpha??0);
             $pct=$jml>0?round(($r->hadir??0)/$jml*100):0;
             $pbcls=$pct>=75?'pb-g':($pct>=50?'pb-b':'pb-r');
             $pclr=$pct>=75?'var(--green)':($pct>=50?'var(--blue)':'var(--red)'); @endphp
        <tr>
          <td class="tm">{{ $i+1 }}</td>
          <td class="fw7">{{ $r->nama }}</td>
          @if(request('tipe')==='guru') <td class="tm">{{ $r->jabatan??'-' }}</td>
          @else <td><span class="badge bg-b">{{ $r->kelas }}</span></td><td class="tm" style="font-family:monospace;font-size:11px;">{{ $r->nis }}</td> @endif
          <td><span class="fw7 tg">{{ $r->hadir??0 }}</span></td>
          <td><span class="fw7" style="color:var(--yel);">{{ $r->izin??0 }}</span></td>
          <td><span class="fw7 tb2">{{ $r->sakit??0 }}</span></td>
          <td><span class="fw7 tr">{{ $r->alpha??0 }}</span></td>
          <td class="fw7">{{ $jml }}</td>
          <td>
            <div class="flex gap2" style="align-items:center;min-width:80px;">
              <div class="prog" style="flex:1;"><div class="prog-b {{ $pbcls }}" style="width:{{ $pct }}%;"></div></div>
              <span style="font-size:11.5px;font-weight:800;color:{{ $pclr }};width:30px;">{{ $pct }}%</span>
            </div>
          </td>
        </tr>
        @empty
        <tr><td colspan="{{ request('tipe')==='guru'?9:10 }}"><div class="empty"><div class="ei">📊</div><p>Belum ada data rekap. Klik Tampilkan setelah mengatur filter.</p></div></td></tr>
        @endforelse
      </tbody>
      @if(count($rekap??[])>0)
      <tfoot>
        <tr style="background:var(--g50);font-weight:800;border-top:2px solid var(--g200);">
          <td colspan="{{ request('tipe')==='guru'?3:4 }}" style="padding:10px 14px;font-weight:800;">TOTAL</td>
          <td style="padding:10px 14px;color:var(--green);">{{ $totalHadir??0 }}</td>
          <td style="padding:10px 14px;color:var(--yel);">{{ $totalIzin??0 }}</td>
          <td style="padding:10px 14px;color:var(--blue);">{{ $totalSakit??0 }}</td>
          <td style="padding:10px 14px;color:var(--red);">{{ $totalAlpha??0 }}</td>
          <td style="padding:10px 14px;">{{ ($totalHadir??0)+($totalIzin??0)+($totalSakit??0)+($totalAlpha??0) }}</td>
          <td></td>
        </tr>
      </tfoot>
      @endif
    </table>
  </div>

  {{-- TTD --}}
  <div id="ttd" style="display:none;padding:20px 24px;justify-content:space-between;">
    <div style="text-align:center;min-width:170px;">
      <div>Mengetahui,</div><div style="font-weight:700;">Kepala Sekolah</div>
      <div style="margin:46px 0 4px;">&nbsp;</div>
      <div style="border-top:1.5px solid #333;padding-top:3px;font-weight:700;">________________________</div>
      <div style="font-size:10.5px;">NIP.</div>
    </div>
    <div style="text-align:center;min-width:170px;">
      <div>Tangerang, {{ now()->translatedFormat('d F Y') }}</div>
      <div style="font-weight:700;">Petugas Absensi</div>
      <div style="margin:46px 0 4px;">&nbsp;</div>
      <div style="border-top:1.5px solid #333;padding-top:3px;font-weight:700;">{{ auth()->user()->name ?? '________________________' }}</div>
      <div style="font-size:10.5px;">NIP.</div>
    </div>
  </div>
</div>

@push('scripts')
<script>
function toggleKelas(v){document.getElementById('wrap-kelas').style.display=v==='guru'?'none':'';}
toggleKelas(document.getElementById('sel-tipe').value);

function cetakRekap(){
  const kop=document.getElementById('kop'); const ttd=document.getElementById('ttd');
  kop.style.display='block'; ttd.style.display='flex';
  setTimeout(()=>{window.print();setTimeout(()=>{kop.style.display='none';ttd.style.display='none';},1500);},150);
}
</script>
<style>
@media print{
  .no-print,.sb,.topbar,form{display:none!important;}
  .main{margin-left:0!important;} .content{padding:0!important;}
  #kop{display:block!important;} #ttd{display:flex!important;}
  .card{box-shadow:none!important;border:1px solid #ddd!important;margin-bottom:0!important;}
  tfoot{display:table-row-group!important;}
}
</style>
@endpush
@endsection