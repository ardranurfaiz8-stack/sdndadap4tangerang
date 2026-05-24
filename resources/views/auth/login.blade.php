<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — SDN Dadap 4</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #C0392B;
            background-image:
                radial-gradient(ellipse at 20% 50%, rgba(255,255,255,0.08) 0%, transparent 60%),
                radial-gradient(ellipse at 80% 20%, rgba(0,0,0,0.15) 0%, transparent 50%),
                radial-gradient(ellipse at 70% 80%, rgba(255,255,255,0.05) 0%, transparent 40%);
            padding: 24px;
        }
        body::before {
            content: ''; position: fixed;
            top: -80px; left: -80px;
            width: 400px; height: 400px;
            border-radius: 50%;
            background: rgba(255,255,255,0.06);
            pointer-events: none;
        }
        body::after {
            content: ''; position: fixed;
            bottom: -100px; right: -60px;
            width: 350px; height: 350px;
            border-radius: 50%;
            background: rgba(0,0,0,0.12);
            pointer-events: none;
        }
        .login-card {
            background: #fff;
            border-radius: 24px;
            padding: 40px 40px 36px;
            width: 100%; max-width: 460px;
            box-shadow: 0 32px 80px rgba(0,0,0,0.25), 0 8px 24px rgba(0,0,0,0.12);
            position: relative; z-index: 1;
            animation: slideUp 0.4s cubic-bezier(0.16, 1, 0.3, 1);
        }
        @keyframes slideUp {
            from { opacity: 0; transform: translateY(24px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .brand { display: flex; align-items: center; gap: 14px; margin-bottom: 32px; }
        .brand-icon {
            width: 52px; height: 52px; border-radius: 14px;
            background: #C0392B; display: flex; align-items: center;
            justify-content: center; color: white;
            font-size: 22px; font-weight: 800; flex-shrink: 0;
            box-shadow: 0 4px 12px rgba(192,57,43,0.4);
        }
        .brand-text h2 { font-size: 16px; font-weight: 700; color: #C0392B; line-height: 1.2; margin-bottom: 2px; }
        .brand-text p  { font-size: 11.5px; color: #888; line-height: 1.4; }
        .greeting h1   { font-size: 26px; font-weight: 800; color: #1a1a1a; margin-bottom: 6px; }
        .greeting p    { font-size: 14px; color: #888; margin-bottom: 28px; }
        .form-label    { font-size: 13.5px; font-weight: 600; color: #333; margin-bottom: 7px; display: block; }
        .form-control, .form-select {
            width: 100%; padding: 13px 16px;
            border: 1.5px solid #e8e8e8; border-radius: 12px;
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 14px; color: #1a1a1a; background: #fff;
            transition: border-color 0.2s, box-shadow 0.2s;
            outline: none; appearance: none; -webkit-appearance: none;
        }
        .form-control::placeholder { color: #bbb; }
        .form-control:focus, .form-select:focus {
            border-color: #C0392B;
            box-shadow: 0 0 0 3px rgba(192,57,43,0.10);
        }
        .form-control.is-invalid, .form-select.is-invalid { border-color: #C0392B; }
        .select-wrapper { position: relative; }
        .select-wrapper::after {
            content: ''; position: absolute;
            right: 16px; top: 50%; transform: translateY(-50%);
            width: 0; height: 0;
            border-left: 5px solid transparent;
            border-right: 5px solid transparent;
            border-top: 6px solid #888;
            pointer-events: none;
        }
        .form-select { padding-right: 40px; cursor: pointer; }
        .mb-form { margin-bottom: 18px; }
        .invalid-feedback-custom { font-size: 12px; color: #C0392B; margin-top: 5px; display: block; }
        .btn-login {
            width: 100%; padding: 14px;
            background: #C0392B; color: white;
            border: none; border-radius: 12px;
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 15px; font-weight: 700; cursor: pointer;
            margin-top: 8px;
            transition: background 0.2s, transform 0.15s, box-shadow 0.2s;
            box-shadow: 0 4px 16px rgba(192,57,43,0.35);
            display: flex; align-items: center; justify-content: center; gap: 8px;
        }
        .btn-login:hover { background: #a93226; box-shadow: 0 6px 20px rgba(192,57,43,0.45); transform: translateY(-1px); }
        .btn-login:active { transform: translateY(0); }
        .alert-error {
            background: #fef2f2; border: 1px solid #fecaca;
            color: #b91c1c; border-radius: 10px;
            padding: 11px 14px; font-size: 13px; margin-bottom: 20px;
        }
    </style>
</head>
<body>

<div class="login-card">

    <div class="brand">
        <div class="brand-icon">S</div>
        <div class="brand-text">
            <h2>SDN Dadap 4</h2>
            <p>Sistem Informasi Sekolah Dasar Negeri Dadap 4<br>Kabupaten Tangerang</p>
        </div>
    </div>

    <div class="greeting">
        <h1>Selamat Datang 👋</h1>
        <p>Masuk untuk mengakses sistem absensi sekolah</p>
    </div>

    @if ($errors->any())
        <div class="alert-error">{{ $errors->first() }}</div>
    @endif

    @if (session('success'))
        <div style="background:#ecfdf5;border:1px solid #a7f3d0;color:#065f46;border-radius:10px;padding:11px 14px;font-size:13px;margin-bottom:20px;">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('login.post') }}" method="POST">
        @csrf

        <div class="mb-form">
            <label class="form-label">Email</label>
            <input type="text" name="username"
                class="form-control {{ $errors->has('username') ? 'is-invalid' : '' }}"
                placeholder="Masukkan username atau email"
                value="{{ old('username') }}"
                autocomplete="username">
            @error('username')
                <span class="invalid-feedback-custom">{{ $message }}</span>
            @enderror
        </div>

        <div class="mb-form">
            <label class="form-label">Password</label>
            <input type="password" name="password"
                class="form-control {{ $errors->has('password') ? 'is-invalid' : '' }}"
                placeholder="Masukkan password"
                autocomplete="current-password">
            @error('password')
                <span class="invalid-feedback-custom">{{ $message }}</span>
            @enderror
        </div>

        <div class="mb-form">
            <label class="form-label">Login sebagai</label>
            <div class="select-wrapper">
                <select name="role" class="form-select {{ $errors->has('role') ? 'is-invalid' : '' }}">
                    <option value="" disabled {{ old('role') ? '' : 'selected' }}>-- Pilih Role --</option>
                    <option value="admin"          {{ old('role') === 'admin'          ? 'selected' : '' }}>Admin</option>
                    <option value="guru"           {{ old('role') === 'guru'           ? 'selected' : '' }}>Guru</option>
                    <option value="siswa"          {{ old('role') === 'siswa'          ? 'selected' : '' }}>Siswa</option>
                    <option value="kepala_sekolah" {{ old('role') === 'kepala_sekolah' ? 'selected' : '' }}>Kepala Sekolah</option>
                </select>
            </div>
            @error('role')
                <span class="invalid-feedback-custom">{{ $message }}</span>
            @enderror
        </div>

        <button type="submit" class="btn-login">
            🔒 Masuk
        </button>
    </form>

</div>

</body>
</html>