@extends('layouts.app')
@section('title', 'Edit Guru')

@section('content')
<div style="max-width:600px;">
    <div class="card">
        <div class="card-header">
            <span class="card-title">✏️ Edit Data Guru</span>
            <a href="{{ route('guru.profil_guru.index') }}" class="btn btn-secondary" style="padding:0.4rem 0.8rem;font-size:0.8rem;">← Kembali</a>
        </div>
        <div class="card-body">

            @if(session('success'))
                <div style="background:#e8f5e9;color:#2e7d32;padding:10px 14px;border-radius:6px;margin-bottom:16px;font-size:13px;">
                    {{ session('success') }}
                </div>
            @endif

            @if($errors->any())
                <div style="background:#ffebee;color:#c62828;padding:10px 14px;border-radius:6px;margin-bottom:16px;font-size:13px;">
                    <ul style="margin:0;padding-left:16px;">
                        @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('guru.profil_guru.update', $guru->id) }}">
                @csrf @method('PUT')

                <div class="form-group">
                    <label>Nama Lengkap *</label>
                    <input type="text" name="nama" value="{{ old('nama', $guru->nama) }}" required>
                    @error('nama')<small style="color:red;font-size:12px;">{{ $message }}</small>@enderror
                </div>

                <div class="form-group">
                    <label>NIP</label>
                    <input type="text" name="nip" value="{{ old('nip', $guru->nip) }}">
                    @error('nip')<small style="color:red;font-size:12px;">{{ $message }}</small>@enderror
                </div>

                <div class="form-group">
                    <label>Jenis Kelamin *</label>
                    <select name="jenis_kelamin" required>
                        <option value="">-- Pilih --</option>
                        {{-- ✅ value harus sama persis dengan isi database --}}
                        <option value="Laki-laki" {{ old('jenis_kelamin', $guru->jenis_kelamin) === 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                        <option value="Perempuan" {{ old('jenis_kelamin', $guru->jenis_kelamin) === 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Jabatan</label>
                    <input type="text" name="jabatan" value="{{ old('jabatan', $guru->jabatan) }}">
                </div>

                <div class="form-group">
                    <label>No. Telepon</label>
                    {{-- ✅ name="no_telp" sesuai kolom database --}}
                    <input type="text" name="no_telp" value="{{ old('no_telp', $guru->no_telp) }}">
                </div>

                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" value="{{ old('email', $guru->email) }}">
                </div>

                <div class="form-group">
                    <label>Alamat</label>
                    <textarea name="alamat" rows="2">{{ old('alamat', $guru->alamat) }}</textarea>
                </div>

                <div style="display:flex;gap:.75rem;justify-content:flex-end;">
                    <a href="{{ route('guru.profil_guru.index') }}" class="btn btn-secondary">Batal</a>
                    <button type="submit" class="btn btn-primary">💾 Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection