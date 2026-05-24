@extends('guru.layout')
@section('title', 'Tambah Absensi Guru')

@section('content')
<div style="max-width:600px;">
    <div class="card">
        <div class="card-hd">
            <div class="card-title">➕ Tambah Absensi Guru</div>
            <a href="{{ route('guru.absen_guru.index') }}" class="btn btn-s btn-sm">← Kembali</a>
        </div>
        <div class="card-body">

            @if(session('error'))
            <div style="background:var(--red-pale);border:1px solid var(--red-mid);border-radius:8px;padding:.75rem 1rem;margin-bottom:1rem;font-size:.85rem;color:var(--red);">
                ❌ {{ session('error') }}
            </div>
            @endif

            <form method="POST" action="{{ route('guru.absen_guru.store') }}">
                @csrf
                <div class="fg">
                    <label class="fl">Pilih Guru *</label>
                    <select name="guru_id" class="fc" required>
                        <option value="">-- Pilih Guru --</option>
                        @foreach($guruList as $g)
                            <option value="{{ $g->id }}" {{ old('guru_id') == $g->id ? 'selected' : '' }}>
                                {{ $g->nama }} — {{ $g->jabatan ?? '-' }}
                            </option>
                        @endforeach
                    </select>
                    @error('guru_id')<small style="color:var(--red);font-size:12px;">{{ $message }}</small>@enderror
                </div>

                <div class="g2">
                    <div class="fg">
                        <label class="fl">Tanggal *</label>
                        <input type="date" name="tanggal" class="fc" value="{{ old('tanggal', date('Y-m-d')) }}" required>
                    </div>
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
                    <div class="fg">
                        <label class="fl">Jam Keluar</label>
                        <input type="time" name="jam_keluar" class="fc" value="{{ old('jam_keluar') }}">
                    </div>
                </div>

                <div class="fg">
                    <label class="fl">Keterangan</label>
                    <input type="text" name="keterangan" class="fc" placeholder="Opsional..." value="{{ old('keterangan') }}">
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