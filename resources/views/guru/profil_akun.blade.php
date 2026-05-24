@extends('layouts.app')

@section('title', 'Profil Akun')

@section('content')

<div style="max-width:700px;">

    {{-- Update Profil --}}
    <div class="card" style="margin-bottom:1.25rem;">
        <div class="card-header">
            <span class="card-title">👤 Ubah Profil</span>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('guru.profil.update') }}">
                @csrf
                <div class="form-group">
                    <label>Nama Lengkap</label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" required>
                    @error('name') <small style="color:var(--red);font-size:12px;">{{ $message }}</small> @enderror
                </div>
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}" required>
                    @error('email') <small style="color:var(--red);font-size:12px;">{{ $message }}</small> @enderror
                </div>
                <button type="submit" class="btn btn-primary">💾 Simpan Profil</button>
            </form>
        </div>
    </div>

    {{-- Update Password --}}
    <div class="card">
        <div class="card-header">
            <span class="card-title">🔐 Ubah Password</span>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('guru.profil.password') }}">
                @csrf
                <div class="form-group">
                    <label>Password Lama</label>
                    <input type="password" name="password_lama" required>
                    @error('password_lama') <small style="color:var(--red);font-size:12px;">{{ $message }}</small> @enderror
                </div>
                <div class="form-group">
                    <label>Password Baru</label>
                    <input type="password" name="password" required>
                    @error('password') <small style="color:var(--red);font-size:12px;">{{ $message }}</small> @enderror
                </div>
                <div class="form-group">
                    <label>Konfirmasi Password Baru</label>
                    <input type="password" name="password_confirmation" required>
                </div>
                <button type="submit" class="btn btn-primary">🔒 Ubah Password</button>
            </form>
        </div>
    </div>

</div>

@endsection
