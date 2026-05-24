@extends('layouts.app')

@section('title', 'Detail Absensi Guru')

@section('content')

<div style="max-width:600px;">
    <div class="card">
        <div class="card-header">
            <span class="card-title">📋 Detail Absensi</span>
            <a href="{{ route('admin.absen-guru.index') }}" class="btn btn-secondary" style="padding:0.4rem 0.8rem;font-size:0.8rem;">← Kembali</a>
        </div>
        <div class="card-body">
            <table style="width:100%;">
                <tr><td style="font-weight:600;width:140px;padding:8px 0;">Nama Guru</td><td style="padding:8px 0;">{{ $absenGuru->guru->nama ?? '-' }}</td></tr>
                <tr><td style="font-weight:600;padding:8px 0;">NIP</td><td style="padding:8px 0;">{{ $absenGuru->guru->nip ?? '-' }}</td></tr>
                <tr><td style="font-weight:600;padding:8px 0;">Tanggal</td><td style="padding:8px 0;">{{ \Carbon\Carbon::parse($absenGuru->tanggal)->translatedFormat('d F Y') }}</td></tr>
                <tr><td style="font-weight:600;padding:8px 0;">Jam Masuk</td><td style="padding:8px 0;">{{ $absenGuru->jam_masuk ?? '-' }}</td></tr>
                <tr><td style="font-weight:600;padding:8px 0;">Status</td><td style="padding:8px 0;"><span class="badge badge-{{ $absenGuru->status }}">{{ ucfirst($absenGuru->status) }}</span></td></tr>
                <tr><td style="font-weight:600;padding:8px 0;">Keterangan</td><td style="padding:8px 0;">{{ $absenGuru->keterangan ?? '-' }}</td></tr>
            </table>
            <div style="margin-top:1.25rem;display:flex;gap:0.75rem;">
                <a href="{{ route('admin.absen-guru.edit', $absenGuru->id) }}" class="btn btn-primary">✏️ Edit</a>
                <form method="POST" action="{{ route('admin.absen-guru.destroy', $absenGuru->id) }}" onsubmit="return confirm('Yakin hapus?')">
                    @csrf @method('DELETE')
                    <button class="btn btn-danger">🗑️ Hapus</button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection
