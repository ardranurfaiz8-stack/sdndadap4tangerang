@extends('guru.layout')
@section('title', 'Edit Absensi Guru')

@section('content')
<div style="max-width:600px;">
    <div class="card">
        <div class="card-hd">
            <div class="card-title">✏️ Edit Absensi Guru</div>
            <a href="{{ route('guru.absen_guru.index') }}" class="btn btn-s btn-sm">← Kembali</a>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('guru.absen_guru.update', $absensi->id) }}">
                @csrf @method('PUT')

                <div class="fg">
                    <label class="fl">Pilih Guru *</label>
                    <select name="guru_id" class="fc" required>
                        @foreach($guruList as $g)
                            <option value="{{ $g->id }}" {{ $absensi->guru_id == $g->id ? 'selected' : '' }}>
                                {{ $g->nama }} — {{ $g->jabatan ?? '-' }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="g2">
                    <div class="fg">
                        <label class="fl">Tanggal *</label>
                        <input type="date" name="tanggal" class="fc"
                               value="{{ old('tanggal', $absensi->tanggal instanceof \Carbon\Carbon ? $absensi->tanggal->format('Y-m-d') : $absensi->tanggal) }}" required>
                    </div>
                    <div class="fg">
                        <label class="fl">Status *</label>
                        <select name="status" class="fc" required>
                            <option value="hadir" {{ $absensi->status=='hadir'?'selected':'' }}>✅ Hadir</option>
                            <option value="izin"  {{ $absensi->status=='izin' ?'selected':'' }}>📄 Izin</option>
                            <option value="sakit" {{ $absensi->status=='sakit'?'selected':'' }}>🏥 Sakit</option>
                            <option value="alpha" {{ $absensi->status=='alpha'?'selected':'' }}>❌ Alpha</option>
                        </select>
                    </div>
                    <div class="fg">
                        <label class="fl">Jam Masuk</label>
                        <input type="time" name="jam_masuk" class="fc" value="{{ old('jam_masuk', $absensi->jam_masuk) }}">
                    </div>
                    <div class="fg">
                        <label class="fl">Jam Keluar</label>
                        <input type="time" name="jam_keluar" class="fc" value="{{ old('jam_keluar', $absensi->jam_keluar) }}">
                    </div>
                </div>

                <div class="fg">
                    <label class="fl">Keterangan</label>
                    <input type="text" name="keterangan" class="fc"
                           placeholder="Opsional..." value="{{ old('keterangan', $absensi->keterangan) }}">
                </div>

                <div style="display:flex;gap:.75rem;justify-content:flex-end;margin-top:1rem;">
                    <a href="{{ route('guru.absen_guru.index') }}" class="btn btn-s">Batal</a>
                    <button type="submit" class="btn btn-r">💾 Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection