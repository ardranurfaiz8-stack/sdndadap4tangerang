@extends('guru.layout')
@section('title','Absensi Siswa')
@section('page-title','Absensi Siswa')

@section('content')

{{-- Stat --}}
<div class="stat-grid">
  <div class="stat-card"><div class="si si-g">✅</div><div><div class="sv">{{ $hadirHariIni ?? 0 }}</div><div class="sl">Hadir Hari Ini</div></div></div>
  <div class="stat-card"><div class="si si-y">📄</div><div><div class="sv">{{ $izinHariIni ?? 0 }}</div><div class="sl">Izin Hari Ini</div></div></div>
  <div class="stat-card"><div class="si si-b">🏥</div><div><div class="sv">{{ $sakitHariIni ?? 0 }}</div><div class="sl">Sakit Hari Ini</div></div></div>
  <div class="stat-card"><div class="si si-r">❌</div><div><div class="sv">{{ $alphaHariIni ?? 0 }}</div><div class="sl">Alpha Hari Ini</div></div></div>
</div>

{{-- Tabel Absensi --}}
<div class="card">
  <div class="card-hd">
    <div class="card-title">📋 Daftar Absensi Siswa</div>
    <div class="flex gap2" style="align-items:center;flex-wrap:wrap;">
      <select class="fc" style="width:130px;" id="flt-kelas" onchange="applyFilter()">
        <option value="">Semua Kelas</option>
        @foreach($kelasList ?? ['1A','1B','2A','2B','3A','3B','4A','4B','5A','5B','6A','6B'] as $k)
        <option value="{{ $k }}" {{ request('kelas')===$k?'selected':'' }}>Kelas {{ $k }}</option>
        @endforeach
      </select>
      <input type="date" class="fc" style="width:150px;" id="flt-tgl"
             value="{{ request('tanggal', date('Y-m-d')) }}" onchange="applyFilter()">
      <select class="fc" style="width:120px;" id="flt-status" onchange="applyFilter()">
        <option value="">Semua Status</option>
        @foreach(['Hadir','Izin','Sakit','Alpha'] as $s)
        <option value="{{ $s }}" {{ request('status')===$s?'selected':'' }}>{{ $s }}</option>
        @endforeach
      </select>
      <a href="{{ route('guru.absen_siswa.create') }}" class="btn btn-r btn-sm no-print">+ Tambah</a>
    </div>
  </div>
  <div class="tw">
    <table id="tbl-siswa">
      <thead>
        <tr>
          <th>#</th><th>Nama Siswa</th><th>Kelas</th><th>NIS</th>
          <th>Tanggal</th><th>Jam Masuk</th><th>Status</th><th>Keterangan</th>
          <th class="no-print">Aksi</th>
        </tr>
      </thead>
      <tbody>
        @forelse($absensi ?? [] as $i => $ab)
        @php
          $ini = strtoupper(collect(explode(' ',$ab->siswa->nama??'S'))->take(2)->map(fn($w)=>$w[0])->join(''));
          $clr = ['#1e88e5','#43a047','#f57c00','#8e24aa','#e53935','#00897b'][$i%6];
        @endphp
        <tr>
          <td class="tm">{{ $i+1 }}</td>
          <td>
            <div class="flex gap2" style="align-items:center;">
              <div class="av" style="background:{{ $clr }};color:#fff;">{{ $ini }}</div>
              <span class="fw7">{{ $ab->siswa->nama ?? '-' }}</span>
            </div>
          </td>
          <td><span class="badge bg-b">{{ $ab->siswa->kelas ?? '-' }}</span></td>
          <td class="tm" style="font-family:monospace;font-size:11px;">{{ $ab->siswa->nis ?? '-' }}</td>
          <td>{{ \Carbon\Carbon::parse($ab->tanggal)->format('d/m/Y') }}</td>
          <td class="fw7">{{ $ab->jam_masuk ?? '–' }}</td>
          <td>
            @if($ab->status==='hadir')     <span class="badge bg-g">Hadir</span>
            @elseif($ab->status==='izin')  <span class="badge bg-y">Izin</span>
            @elseif($ab->status==='sakit') <span class="badge bg-b">Sakit</span>
            @else                          <span class="badge bg-r">Alpha</span>
            @endif
          </td>
          <td class="tm">{{ $ab->keterangan ?? '–' }}</td>
          <td class="no-print">
            <a href="{{ route('guru.absen_siswa.edit', $ab->id) }}" class="btn btn-s btn-sm">✏️ Edit</a>
            <form id="hs-{{ $ab->id }}" method="POST" action="{{ route('guru.absen_siswa.destroy',$ab->id) }}" style="display:none;">
              @csrf @method('DELETE')
            </form>
            <button class="btn btn-sm" style="background:#FFEBEE;color:#c0392b;border:none;"
              onclick="if(confirm('Hapus absensi ini?')) document.getElementById('hs-{{ $ab->id }}').submit()">
              🗑️
            </button>
          </td>
        </tr>
        @empty
        <tr><td colspan="9"><div class="empty"><div class="ei">🧑‍🎓</div><p>Belum ada data absensi siswa</p></div></td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
  @if(isset($absensi) && method_exists($absensi,'hasPages') && $absensi->hasPages())
  <div style="padding:10px 14px;border-top:1px solid var(--g200);">{{ $absensi->withQueryString()->links() }}</div>
  @endif
</div>

{{-- Modal Tambah --}}
<div class="mo" id="mo-tambah-siswa">
  <div class="modal">
    <div class="mh"><div class="mt">➕ Tambah Absensi Siswa</div><button class="mx" onclick="closeModal('mo-tambah-siswa')">✕</button></div>
    <form method="POST" action="{{ route('guru.absen_siswa.store') }}">
      @csrf
      <div class="mb">
        <div class="g2">
          <div class="fg"><label class="fl">Kelas</label>
            <select class="fc" onchange="loadSiswaModal(this.value)">
              <option value="">-- Pilih Kelas --</option>
              @foreach($kelasList ?? ['1A','1B','2A','2B','3A','3B','4A','4B','5A','5B','6A','6B'] as $k)
              <option value="{{ $k }}">Kelas {{ $k }}</option>
              @endforeach
            </select></div>
          <div class="fg"><label class="fl">Tanggal *</label>
            <input type="date" name="tanggal" class="fc" value="{{ date('Y-m-d') }}" required></div>
        </div>
        <div class="fg"><label class="fl">Pilih Siswa *</label>
          <select name="siswa_id" class="fc" id="sel-siswa-modal" required>
            <option value="">-- Pilih kelas dahulu --</option>
            @foreach($siswaList ?? [] as $s)
            <option value="{{ $s->id }}">{{ $s->nama }} ({{ $s->nis }}) — {{ $s->kelas }}</option>
            @endforeach
          </select></div>
        <div class="g2">
          <div class="fg"><label class="fl">Status *</label>
            <select name="status" class="fc" required>
              <option value="Hadir">✅ Hadir</option><option value="Izin">📄 Izin</option>
              <option value="Sakit">🏥 Sakit</option><option value="Alpha">❌ Alpha</option>
            </select></div>
          <div class="fg"><label class="fl">Jam Masuk</label>
            <input type="time" name="jam_masuk" class="fc" value="{{ date('H:i') }}"></div>
        </div>
        <div class="fg"><label class="fl">Keterangan</label>
          <input type="text" name="keterangan" class="fc" placeholder="Opsional..."></div>
      </div>
      <div class="mf">
        <button type="button" class="btn btn-s" onclick="closeModal('mo-tambah-siswa')">Batal</button>
        <button type="submit" class="btn btn-r">💾 Simpan</button>
      </div>
    </form>
  </div>
</div>

{{-- Modal Edit --}}
<div class="mo" id="mo-edit-siswa">
  <div class="modal">
    <div class="mh"><div class="mt">✏️ Edit Absensi Siswa</div><button class="mx" onclick="closeModal('mo-edit-siswa')">✕</button></div>
    <form method="POST" id="form-edit-siswa">
      @csrf @method('PUT')
      <div class="mb">
        <div class="fg"><label class="fl">Siswa *</label>
          <select name="siswa_id" id="es-siswa" class="fc" required>
            @foreach($siswaList ?? [] as $s)
            <option value="{{ $s->id }}">{{ $s->nama }} ({{ $s->nis }})</option>
            @endforeach
          </select></div>
        <div class="g2">
          <div class="fg"><label class="fl">Tanggal *</label><input type="date" name="tanggal" id="es-tgl" class="fc" required></div>
          <div class="fg"><label class="fl">Status *</label>
            <select name="status" id="es-status" class="fc" required>
              <option value="Hadir">✅ Hadir</option><option value="Izin">📄 Izin</option>
              <option value="Sakit">🏥 Sakit</option><option value="Alpha">❌ Alpha</option>
            </select></div>
          <div class="fg"><label class="fl">Jam Masuk</label><input type="time" name="jam_masuk" id="es-masuk" class="fc"></div>
        </div>
        <div class="fg"><label class="fl">Keterangan</label><input type="text" name="keterangan" id="es-ket" class="fc"></div>
      </div>
      <div class="mf">
        <button type="button" class="btn btn-s" onclick="closeModal('mo-edit-siswa')">Batal</button>
        <button type="submit" class="btn btn-r">💾 Simpan</button>
      </div>
    </form>
  </div>
</div>

@push('scripts')
<script>
function applyFilter(){
  const k=document.getElementById('flt-kelas').value;
  const t=document.getElementById('flt-tgl').value;
  const s=document.getElementById('flt-status').value;
  window.location.href='?kelas='+k+'&tanggal='+t+'&status='+s;
}
function editSiswa(id,siswaId,tgl,masuk,status,ket){
  document.getElementById('form-edit-siswa').action='{{ url("guru/absen_siswa") }}/'+id;
  document.getElementById('es-siswa').value=siswaId;
  document.getElementById('es-tgl').value=tgl;
  document.getElementById('es-masuk').value=masuk;
  document.getElementById('es-status').value=status;
  document.getElementById('es-ket').value=ket;
  openModal('mo-edit-siswa');
}
function loadSiswaModal(kelas){
  if(!kelas)return;
  fetch('{{ route("guru.api.siswa-by-kelas") }}?kelas='+kelas)
    .then(r=>r.json()).then(data=>{
      const sel=document.getElementById('sel-siswa-modal');
      sel.innerHTML='<option value="">-- Pilih Siswa --</option>';
      data.forEach(s=>sel.innerHTML+=`<option value="${s.id}">${s.nama} (${s.nis})</option>`);
    }).catch(()=>{});
}
</script>
@endpush

@endsection