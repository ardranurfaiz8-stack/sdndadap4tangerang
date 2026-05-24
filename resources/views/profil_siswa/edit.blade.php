@extends('layouts.app')
@section('title', 'Edit Siswa')

@section('content')
<div style="max-width:600px;">
    <div class="card">
        <div class="card-header">
            <span class="card-title">✏️ Edit Data Siswa</span>
            <a href="{{ route('guru.profil_siswa.index') }}" class="btn btn-secondary"
               style="padding:0.4rem 0.8rem;font-size:0.8rem;">← Kembali</a>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('guru.profil_siswa.update', $siswa->id) }}">
                @csrf @method('PUT')
                <div class="form-group">
                    <label>Nama Lengkap *</label>
                    <input type="text" name="nama" value="{{ old('nama', $siswa->nama) }}" required>
                    @error('nama')<small style="color:var(--red);font-size:12px;">{{ $message }}</small>@enderror
                </div>
                <div class="form-group">
                    <label>NIS *</label>
                    <input type="text" name="nis" value="{{ old('nis', $siswa->nis) }}" required>
                    @error('nis')<small style="color:var(--red);font-size:12px;">{{ $message }}</small>@enderror
                </div>
                <div class="form-group">
                    <label>Kelas *</label>
                    <select name="kelas" required>
                        @foreach($kelasList as $k)
                            <option value="{{ $k }}" {{ old('kelas', $siswa->kelas)==$k?'selected':'' }}>{{ $k }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label>Jenis Kelamin *</label>
                    <select name="jenis_kelamin" required>
                        <option value="Laki-laki" {{ old('jenis_kelamin', $siswa->jenis_kelamin)=='Laki-laki'?'selected':'' }}>Laki-laki</option>
                        <option value="Perempuan" {{ old('jenis_kelamin', $siswa->jenis_kelamin)=='Perempuan'?'selected':'' }}>Perempuan</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Tempat Lahir</label>
                    <input type="text" name="tempat_lahir" value="{{ old('tempat_lahir', $siswa->tempat_lahir) }}">
                </div>
                <div class="form-group">
                    <label>Tanggal Lahir</label>
                    <input type="date" name="tanggal_lahir"
                           value="{{ old('tanggal_lahir', $siswa->tanggal_lahir ? \Carbon\Carbon::parse($siswa->tanggal_lahir)->format('Y-m-d') : '') }}">
                </div>
                <div class="form-group">
                    <label>Nama Orang Tua</label>
                    <input type="text" name="nama_orang_tua" value="{{ old('nama_orang_tua', $siswa->nama_orang_tua) }}">
                </div>
                <div class="form-group">
                    <label>No. Telepon Orang Tua</label>
                    <input type="text" name="no_telp" value="{{ old('no_telp', $siswa->no_telp) }}">
                </div>
                <div class="form-group">
                    <label>Alamat</label>
                    <textarea name="alamat" rows="2">{{ old('alamat', $siswa->alamat) }}</textarea>
                </div>
                <div style="display:flex;gap:.75rem;justify-content:flex-end;">
                    <a href="{{ route('guru.profil_siswa.index') }}" class="btn btn-secondary">Batal</a>
                    <button type="submit" class="btn btn-primary">💾 Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection