@extends('layouts.app')
@section('title', 'Tambah Guru')

@section('content')
<div style="max-width:600px;">
    <div class="card">
        <div class="card-header">
            <span class="card-title">➕ Tambah Data Guru</span>
            <a href="{{ route('guru.profil_guru.index') }}" class="btn btn-secondary"
               style="padding:0.4rem 0.8rem;font-size:0.8rem;">← Kembali</a>
        </div>
        <div class="card-body">

            @if($errors->any())
            <div style="background:#FDEDEC;border:1px solid #F1948A;border-radius:8px;padding:.75rem 1rem;margin-bottom:1rem;font-size:.85rem;color:#C0392B;">
                <ul style="margin:0;padding-left:1.2rem;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <form method="POST" action="{{ route('guru.profil_guru.store') }}">
                @csrf
                <div class="form-group">
                    <label>Nama Lengkap *</label>
                    <input type="text" name="nama" value="{{ old('nama') }}" required
                           style="width:100%;padding:.65rem .9rem;border:1.5px solid #E8EAED;border-radius:10px;font-family:inherit;font-size:.875rem;outline:none;">
                    @error('nama')<small style="color:#C0392B;font-size:12px;">{{ $message }}</small>@enderror
                </div>
                <div class="form-group">
                    <label>NIP</label>
                    <input type="text" name="nip" value="{{ old('nip') }}"
                           style="width:100%;padding:.65rem .9rem;border:1.5px solid #E8EAED;border-radius:10px;font-family:inherit;font-size:.875rem;outline:none;">
                    @error('nip')<small style="color:#C0392B;font-size:12px;">{{ $message }}</small>@enderror
                </div>
                <div class="form-group">
                    <label>Jenis Kelamin *</label>
                    <select name="jenis_kelamin" required
                            style="width:100%;padding:.65rem .9rem;border:1.5px solid #E8EAED;border-radius:10px;font-family:inherit;font-size:.875rem;outline:none;background:white;">
                        <option value="">-- Pilih --</option>
                        <option value="Laki-laki" {{ old('jenis_kelamin')=='Laki-laki'?'selected':'' }}>Laki-laki</option>
                        <option value="Perempuan" {{ old('jenis_kelamin')=='Perempuan'?'selected':'' }}>Perempuan</option>
                    </select>
                    @error('jenis_kelamin')<small style="color:#C0392B;font-size:12px;">{{ $message }}</small>@enderror
                </div>
                <div class="form-group">
                    <label>Jabatan</label>
                    <input type="text" name="jabatan" value="{{ old('jabatan') }}"
                           style="width:100%;padding:.65rem .9rem;border:1.5px solid #E8EAED;border-radius:10px;font-family:inherit;font-size:.875rem;outline:none;">
                </div>
                <div class="form-group">
                    <label>No. Telepon</label>
                    <input type="text" name="no_hp" value="{{ old('no_hp') }}"
                           style="width:100%;padding:.65rem .9rem;border:1.5px solid #E8EAED;border-radius:10px;font-family:inherit;font-size:.875rem;outline:none;">
                </div>
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" value="{{ old('email') }}"
                           style="width:100%;padding:.65rem .9rem;border:1.5px solid #E8EAED;border-radius:10px;font-family:inherit;font-size:.875rem;outline:none;">
                </div>
                <div class="form-group">
                    <label>Alamat</label>
                    <textarea name="alamat" rows="3"
                              style="width:100%;padding:.65rem .9rem;border:1.5px solid #E8EAED;border-radius:10px;font-family:inherit;font-size:.875rem;outline:none;">{{ old('alamat') }}</textarea>
                </div>
                <div style="display:flex;gap:.75rem;justify-content:flex-end;margin-top:1rem;">
                    <a href="{{ route('guru.profil_guru.index') }}" class="btn btn-secondary">Batal</a>
                    <button type="submit" class="btn btn-primary">💾 Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection