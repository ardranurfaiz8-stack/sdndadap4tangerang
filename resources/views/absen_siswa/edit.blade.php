@extends('guru.layout')
@section('title', 'Edit Absensi Siswa')

@section('content')
<div style="max-width:600px;">
    <div class="card">
        <div class="card-hd">
            <div class="card-title">✏️ Edit Absensi Siswa</div>
            <a href="{{ route('guru.absen_siswa.index') }}" class="btn btn-s btn-sm">← Kembali</a>
        </div>
        <div class="card-body">

            <div style="display:flex;align-items:center;gap:12px;background:var(--red-pale);border-radius:8px;padding:.9rem 1rem;margin-bottom:1.25rem;">
                <div style="width:42px;height:42px;border-radius:50%;background:linear-gradient(135deg,var(--red),var(--red-light));display:flex;align-items:center;justify-content:center;color:white;font-weight:800;font-size:15px;flex-shrink:0;">
                    {{ strtoupper(substr($absensi->siswa->nama ?? 'S', 0, 2)) }}
                </div>
                <div>
                    <div style="font-weight:700;font-size:.9rem;">{{ $absensi->siswa->nama ?? '-' }}</div>
                    <div style="font-size:.72rem;color:var(--gray-500);">
                        Kelas {{ $absensi->siswa->kelas ?? '-' }} · NIS: {{ $absensi->siswa->nis ?? '-' }}
                    </div>
                </div>
            </div>

            <form method="POST" action="{{ route('guru.absen_siswa.update', $absensi->id) }}">
                @csrf @method('PUT')

                <div class="fg">
                    <label class="fl">Pilih Siswa *</label>
                    <select name="siswa_id" class="fc" required>
                        @foreach($siswaList as $s)
                            <option value="{{ $s->id }}" {{ $absensi->siswa_id == $s->id ? 'selected' : '' }}>
                                {{ $s->nama }} ({{ $s->nis }}) — {{ $s->kelas }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="g2">
                    <div class="fg">
                        <label class="fl">Tanggal *</label>
                        <input type="date" name="tanggal" class="fc" value="{{ old('tanggal', $absensi->tanggal) }}" required>
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
                </div>

                <div class="fg">
                    <label class="fl">Keterangan</label>
                    <input type="text" name="keterangan" class="fc" placeholder="Opsional..." value="{{ old('keterangan', $absensi->keterangan) }}">
                </div>

                <div style="display:flex;gap:.75rem;justify-content:flex-end;margin-top:1rem;">
                    <a href="{{ route('guru.absen_siswa.index') }}" class="btn btn-s">Batal</a>
                    <button type="submit" class="btn btn-r">💾 Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection