@extends('guru.layout')
@section('title', 'Tambah Absensi Siswa')

@section('content')
<div style="max-width:600px;">
    <div class="card">
        <div class="card-hd">
            <div class="card-title">➕ Tambah Absensi Siswa</div>
            <a href="{{ route('guru.absen_siswa.index') }}" class="btn btn-s btn-sm">← Kembali</a>
        </div>
        <div class="card-body">

            @if(session('error'))
                <div style="background:var(--red-pale);border:1px solid var(--red-mid);border-radius:8px;padding:.75rem 1rem;margin-bottom:1rem;font-size:.85rem;color:var(--red);">
                    ❌ {{ session('error') }}
                </div>
            @endif

            <form method="POST" action="{{ route('guru.absen_siswa.store') }}">
                @csrf
                <div class="g2">
                    <div class="fg">
                        <label class="fl">Kelas</label>
                        <select class="fc" id="sel-kelas" onchange="loadSiswa(this.value)">
                            <option value="">-- Pilih Kelas --</option>
                            @foreach($kelasList as $k)
                                <option value="{{ $k }}">Kelas {{ $k }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="fg">
                        <label class="fl">Tanggal *</label>
                        <input type="date" name="tanggal" class="fc" value="{{ old('tanggal', date('Y-m-d')) }}" required>
                    </div>
                </div>

                <div class="fg">
                    <label class="fl">Pilih Siswa *</label>
                    <select name="siswa_id" class="fc" id="sel-siswa" required>
                        <option value="">-- Pilih kelas dahulu --</option>
                        @foreach($siswaList as $s)
                            <option value="{{ $s->id }}" {{ old('siswa_id') == $s->id ? 'selected' : '' }}>
                                {{ $s->nama }} ({{ $s->nis }}) — {{ $s->kelas }}
                            </option>
                        @endforeach
                    </select>
                    @error('siswa_id') <small style="color:var(--red);font-size:12px;">{{ $message }}</small> @enderror
                </div>

                <div class="g2">
                    <div class="fg">
                        <label class="fl">Status *</label>
                        <select name="status" class="fc" required>
                        <option value="hadir" {{ old('status')=='hadir'?'selected':'' }}>✅ Hadir</option>
                        <option value="izin"  {{ old('status')=='izin' ?'selected':'' }}>📄 Izin</option>
                        <option value="sakit" {{ old('status')=='sakit'?'selected':'' }}>🏥 Sakit</option>
                        <option value="alpha" {{ old('status')=='alpha'?'selected':'' }}>❌ Alpha</option>
                    </select>
                                        </div>
                    <div class="fg">
                        <label class="fl">Jam Masuk</label>
                        <input type="time" name="jam_masuk" class="fc" value="{{ old('jam_masuk', date('H:i')) }}">
                    </div>
                </div>

                <div class="fg">
                    <label class="fl">Keterangan</label>
                    <input type="text" name="keterangan" class="fc" placeholder="Opsional..." value="{{ old('keterangan') }}">
                </div>

                <div style="display:flex;gap:.75rem;justify-content:flex-end;margin-top:1rem;">
                    <a href="{{ route('guru.absen_siswa.index') }}" class="btn btn-s">Batal</a>
                    <button type="submit" class="btn btn-r">💾 Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
function loadSiswa(kelas) {
    if (!kelas) return;
    fetch('{{ route("guru.api.siswa-by-kelas") }}?kelas=' + kelas)
        .then(r => r.json()).then(data => {
            const sel = document.getElementById('sel-siswa');
            sel.innerHTML = '<option value="">-- Pilih Siswa --</option>';
            data.forEach(s => sel.innerHTML += `<option value="${s.id}">${s.nama} (${s.nis})</option>`);
        }).catch(() => {});
}
</script>
@endpush
@endsection