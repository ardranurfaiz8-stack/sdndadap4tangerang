<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Sistem Absensi') — SDN Dadap 4</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.min.js"></script>

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');

        :root {
            --red: #C0392B;
            --red-dark: #922B21;
            --red-light: #E74C3C;
            --red-pale: #FDEDEC;
            --red-mid: #F1948A;
            --white: #FFFFFF;
            --gray-50: #F8F9FA;
            --gray-100: #F1F3F4;
            --gray-200: #E8EAED;
            --gray-300: #DADCE0;
            --gray-500: #9AA0A6;
            --gray-600: #80868B;
            --gray-700: #5F6368;
            --gray-900: #202124;
            --shadow-sm: 0 1px 3px rgba(0,0,0,0.08), 0 1px 2px rgba(0,0,0,0.06);
            --shadow-md: 0 4px 12px rgba(0,0,0,0.10), 0 2px 6px rgba(0,0,0,0.06);
            --shadow-lg: 0 10px 30px rgba(0,0,0,0.12), 0 4px 12px rgba(0,0,0,0.08);
            --radius: 12px;
            --radius-lg: 16px;
            --radius-xl: 24px;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: var(--gray-50);
            color: var(--gray-900);
            min-height: 100vh;
            overflow-x: hidden;
        }

        /* ======= SIDEBAR ======= */
        .sidebar {
            width: 260px;
            background: white;
            border-right: 1px solid var(--gray-200);
            display: flex;
            flex-direction: column;
            position: fixed;
            top: 0; left: 0;
            height: 100vh;
            z-index: 100;
            box-shadow: var(--shadow-sm);
            transition: transform 0.3s;
        }
        .sidebar-logo {
            padding: 1.25rem 1.25rem 1rem;
            border-bottom: 1px solid var(--gray-100);
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .sidebar-logo-icon {
            width: 42px; height: 42px;
            background: linear-gradient(135deg, var(--red), var(--red-light));
            border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            color: white; font-weight: 800; font-size: 18px; flex-shrink: 0;
        }
        .sidebar-logo-text h2 { font-size: 1rem; font-weight: 800; color: var(--red); line-height: 1.2; }
        .sidebar-logo-text p { font-size: 0.68rem; color: var(--gray-500); }

        .sidebar-user {
            padding: 0.9rem 1.25rem;
            border-bottom: 1px solid var(--gray-100);
            display: flex; align-items: center; gap: 10px;
        }
        .user-avatar {
            width: 36px; height: 36px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--red), var(--red-light));
            display: flex; align-items: center; justify-content: center;
            color: white; font-size: 13px; font-weight: 700; flex-shrink: 0;
        }
        .user-info-text h4 { font-size: 0.8rem; font-weight: 700; color: var(--gray-900); }
        .user-info-text p { font-size: 0.68rem; color: var(--gray-500); }
        .role-badge {
            display: inline-block; padding: 2px 8px; border-radius: 20px;
            font-size: 0.65rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px;
        }
        .role-admin          { background: var(--red-pale); color: var(--red); }
        .role-guru           { background: #E3F2FD; color: #1565C0; }
        .role-kepala_sekolah { background: #E8F5E9; color: #2E7D32; }
        .role-siswa          { background: #FFF8E1; color: #F57F17; }

        .sidebar-nav { flex: 1; padding: 0.75rem 0; overflow-y: auto; }
        .nav-section-title {
            padding: 0.5rem 1.25rem 0.3rem;
            font-size: 0.65rem; font-weight: 700;
            text-transform: uppercase; letter-spacing: 1px; color: var(--gray-500);
        }
        .nav-item {
            display: flex; align-items: center; gap: 10px;
            padding: 0.6rem 1.25rem; margin: 1px 0.5rem;
            border-radius: 10px; cursor: pointer;
            font-size: 0.83rem; font-weight: 500; color: var(--gray-700);
            transition: all 0.15s; text-decoration: none;
        }
        .nav-item:hover { background: var(--gray-100); color: var(--gray-900); }
        .nav-item.active { background: var(--red-pale); color: var(--red); font-weight: 600; }
        .nav-item .nav-icon { font-size: 18px; width: 22px; text-align: center; flex-shrink: 0; }

        .sidebar-footer {
            padding: 0.75rem 1rem;
            border-top: 1px solid var(--gray-100);
        }
        .logout-btn {
            display: flex; align-items: center; gap: 10px;
            padding: 0.6rem 1rem; border-radius: 10px; cursor: pointer;
            font-size: 0.83rem; font-weight: 600; color: var(--red);
            background: var(--red-pale); border: none; width: 100%;
            font-family: inherit; transition: all 0.2s;
        }
        .logout-btn:hover { background: var(--red); color: white; }

        /* ======= MAIN ======= */
        .main { margin-left: 260px; flex: 1; min-height: 100vh; }
        .topbar {
            background: white; border-bottom: 1px solid var(--gray-200);
            padding: 0 1.75rem; height: 62px;
            display: flex; align-items: center; justify-content: space-between;
            position: sticky; top: 0; z-index: 50;
        }
        .page-title { font-size: 1.1rem; font-weight: 800; color: var(--gray-900); }
        .topbar-right { display: flex; align-items: center; gap: 0.75rem; }
        .topbar-date { font-size: 0.78rem; color: var(--gray-500); }
        .content { padding: 1.5rem 1.75rem; }

        /* ======= CARDS ======= */
        .card {
            background: white;
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-sm);
            border: 1px solid var(--gray-200);
        }
        .card-header {
            padding: 1.25rem 1.5rem;
            border-bottom: 1px solid var(--gray-100);
            display: flex; align-items: center; justify-content: space-between;
        }
        .card-title { font-size: 0.95rem; font-weight: 700; color: var(--gray-900); }
        .card-body { padding: 1.25rem 1.5rem; }

        /* ======= STAT CARDS ======= */
        .stats-grid {
            display: grid; grid-template-columns: repeat(4, 1fr); gap: 1rem; margin-bottom: 1.5rem;
        }
        .stat-card {
            background: white; border-radius: var(--radius-lg);
            padding: 1.25rem; box-shadow: var(--shadow-sm);
            border: 1px solid var(--gray-200);
            display: flex; align-items: center; gap: 1rem;
        }
        .stat-icon {
            width: 50px; height: 50px; border-radius: 14px;
            display: flex; align-items: center; justify-content: center;
            font-size: 22px; flex-shrink: 0;
        }
        .stat-icon.red   { background: var(--red-pale); }
        .stat-icon.blue  { background: #E3F2FD; }
        .stat-icon.green { background: #E8F5E9; }
        .stat-icon.amber { background: #FFF8E1; }
        .stat-value { font-size: 1.6rem; font-weight: 800; color: var(--gray-900); line-height: 1; }
        .stat-label { font-size: 0.75rem; color: var(--gray-500); margin-top: 2px; }

        /* ======= DASH GRID ======= */
        .dash-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem; }

        /* ======= TABLE ======= */
        .table-wrap { overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; font-size: 0.83rem; }
        th {
            padding: 0.65rem 1rem; text-align: left;
            font-size: 0.7rem; font-weight: 700;
            text-transform: uppercase; letter-spacing: 0.5px;
            color: var(--gray-500); background: var(--gray-50);
            border-bottom: 1px solid var(--gray-200);
        }
        td { padding: 0.8rem 1rem; border-bottom: 1px solid var(--gray-100); color: var(--gray-700); }
        tr:hover td { background: var(--gray-50); }
        tr:last-child td { border-bottom: none; }

        /* ======= BADGES ======= */
        .badge {
            display: inline-block; padding: 3px 10px;
            border-radius: 20px; font-size: 0.7rem; font-weight: 700;
        }
        .badge-hadir { background: #E8F5E9; color: #2E7D32; }
        .badge-sakit { background: #E3F2FD; color: #1565C0; }
        .badge-izin  { background: #FFF8E1; color: #F57F17; }
        .badge-alpha { background: var(--red-pale); color: var(--red); }

        /* ======= BUTTONS ======= */
        .btn {
            padding: 0.7rem 1.2rem; border: none; border-radius: 10px;
            font-family: inherit; font-size: 0.875rem; font-weight: 600;
            cursor: pointer; transition: all 0.2s;
            display: inline-flex; align-items: center; gap: 6px;
        }
        .btn-primary {
            background: linear-gradient(135deg, var(--red), var(--red-light));
            color: white; justify-content: center;
        }
        .btn-primary:hover { transform: translateY(-1px); box-shadow: 0 4px 12px rgba(192,57,43,0.35); color: white; }
        .btn-secondary { background: var(--gray-100); color: var(--gray-700); }
        .btn-secondary:hover { background: var(--gray-200); color: var(--gray-900); }
        .btn-danger { background: var(--red-pale); color: var(--red); }
        .btn-danger:hover { background: var(--red); color: white; }
        .btn-success { background: #E8F5E9; color: #2E7D32; border: 1.5px solid #A5D6A7; }
        .btn-success:hover { background: #C8E6C9; }

        /* ======= QUICK LINK BUTTONS ======= */
        .quick-link-btn {
            display: flex; align-items: center; gap: 10px;
            padding: 0.7rem 1rem; border-radius: 10px;
            background: var(--gray-50); border: 1px solid var(--gray-200);
            color: var(--gray-700); font-size: 0.85rem; font-weight: 600;
            text-decoration: none; transition: all 0.15s;
        }
        .quick-link-btn:hover {
            background: var(--red-pale); border-color: var(--red-mid);
            color: var(--red); transform: translateX(3px);
        }

        /* ======= FORM ======= */
        .form-group { margin-bottom: 1.1rem; }
        .form-group label {
            display: block; font-size: 0.8rem; font-weight: 600;
            color: var(--gray-700); margin-bottom: 6px;
        }
        .form-group input, .form-group select, .form-group textarea {
            width: 100%; padding: 0.65rem 0.9rem;
            border: 1.5px solid var(--gray-200); border-radius: 10px;
            font-family: inherit; font-size: 0.875rem; color: var(--gray-900);
            background: white; transition: all 0.2s; outline: none;
        }
        .form-group input:focus, .form-group select:focus, .form-group textarea:focus {
            border-color: var(--red); box-shadow: 0 0 0 3px rgba(192,57,43,0.12);
        }

        /* ======= SCROLLBAR ======= */
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: var(--gray-300); border-radius: 3px; }
        ::-webkit-scrollbar-thumb:hover { background: var(--gray-500); }

        /* ======= RESPONSIVE ======= */
        @media (max-width: 768px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.open { transform: translateX(0); }
            .main { margin-left: 0; }
            .stats-grid { grid-template-columns: repeat(2, 1fr); }
            .dash-grid { grid-template-columns: 1fr; }
        }
        /* ======= GURU LAYOUT STYLES ======= */
        .mo { display:none; position:fixed !important; inset:0 !important; background:rgba(0,0,0,.5) !important; z-index:9999 !important; align-items:center; justify-content:center; }
        .modal { background:#fff; border-radius:16px; width:100%; max-width:520px; margin:1rem; box-shadow:var(--shadow-lg); max-height:90vh; overflow-y:auto; }
        .mh { display:flex; align-items:center; justify-content:space-between; padding:1.25rem 1.5rem; border-bottom:1px solid var(--gray-100); }
        .mt { font-size:1rem; font-weight:700; }
        .mx { background:var(--gray-100); border:none; border-radius:8px; width:32px; height:32px; cursor:pointer; font-size:16px; display:flex; align-items:center; justify-content:center; }
        .mb { padding:1.25rem 1.5rem; }
        .mf { padding:1rem 1.5rem; border-top:1px solid var(--gray-100); display:flex; gap:.75rem; justify-content:flex-end; }
        .card-hd { padding:1rem 1.25rem; border-bottom:1px solid var(--gray-100); display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:.5rem; }
        .tw { overflow-x:auto; }
        .g2 { display:grid; grid-template-columns:1fr 1fr; gap:1rem; }
        .fg { margin-bottom:.75rem; }
        .fl { display:block; font-size:.78rem; font-weight:600; color:var(--gray-700); margin-bottom:4px; }
        .fc { width:100%; padding:.55rem .75rem; border:1.5px solid var(--gray-200); border-radius:8px; font-family:inherit; font-size:.83rem; color:var(--gray-900); outline:none; background:#fff; transition:border-color .2s; }
        .fc:focus { border-color:var(--red); box-shadow:0 0 0 3px rgba(192,57,43,.1); }
        .fw7 { font-weight:700; }
        .tm { font-size:.82rem; color:var(--gray-500); }
        .flex { display:flex; }
        .gap2 { gap:.5rem; }
        .stat-grid { display:grid; grid-template-columns:repeat(4,1fr); gap:.75rem; margin-bottom:1.25rem; }
        .si { width:44px; height:44px; border-radius:12px; display:flex; align-items:center; justify-content:center; font-size:20px; flex-shrink:0; }
        .si-g { background:#E8F5E9; } .si-y { background:#FFF8E1; } .si-b { background:#E3F2FD; } .si-r { background:var(--red-pale); }
        .sv { font-size:1.5rem; font-weight:800; color:var(--gray-900); line-height:1; }
        .sl { font-size:.72rem; color:var(--gray-500); margin-top:2px; }
        .av { width:32px; height:32px; border-radius:50%; display:flex; align-items:center; justify-content:center; font-size:11px; font-weight:800; flex-shrink:0; }
        .badge.bg-g { background:#E8F5E9; color:#2E7D32; }
        .badge.bg-y { background:#FFF8E1; color:#F57F17; }
        .badge.bg-b { background:#E3F2FD; color:#1565C0; }
        .badge.bg-r { background:var(--red-pale); color:var(--red); }
        .btn-r { background:linear-gradient(135deg,var(--red),var(--red-light)); color:#fff; }
        .btn-r:hover { transform:translateY(-1px); box-shadow:0 4px 12px rgba(192,57,43,.35); color:#fff; }
        .btn-s { background:var(--gray-100); color:var(--gray-700); }
        .btn-sm { padding:.4rem .7rem; font-size:.78rem; }
        .empty { text-align:center; padding:2rem; color:var(--gray-400); }
        .ei { font-size:2.5rem; margin-bottom:.5rem; }
        .qr-grid { display:grid; grid-template-columns:repeat(auto-fill,minmax(160px,1fr)); gap:14px; }
        .qr-card { background:#fff; border:1px solid var(--gray-200); border-radius:12px; padding:14px; text-align:center; }
        .qr-av { width:36px; height:36px; border-radius:50%; color:#fff; display:flex; align-items:center; justify-content:center; font-size:13px; font-weight:800; margin:0 auto 8px; }
        .qr-name { font-size:12.5px; font-weight:700; color:var(--gray-900); }
        .qr-sub { font-size:11px; color:var(--gray-500); margin-bottom:8px; }
        .qr-nip { font-size:10px; color:var(--gray-400); font-family:monospace; margin-top:6px; }
        .scanner-box { background:#1a1a1a; border-radius:12px; display:flex; flex-direction:column; align-items:center; justify-content:center; gap:8px; position:relative; overflow:hidden; padding:20px; }
        .scanner-corner { position:absolute; width:20px; height:20px; border:3px solid var(--red); }
        .sc-tl { top:10px; left:10px; border-right:none; border-bottom:none; }
        .sc-tr { top:10px; right:10px; border-left:none; border-bottom:none; }
        .sc-bl { bottom:10px; left:10px; border-right:none; border-top:none; }
        .sc-br { bottom:10px; right:10px; border-left:none; border-top:none; }
        .scanner-line { position:absolute; left:10px; right:10px; height:2px; background:var(--red); top:50%; opacity:0; }
        .scanner-line.active { opacity:1; animation:scanAnim 1.5s ease-in-out infinite; }
        @keyframes scanAnim { 0%,100%{top:15%} 50%{top:85%} }
        @media(max-width:768px) { .stat-grid{grid-template-columns:repeat(2,1fr)} .g2{grid-template-columns:1fr} }
        @media print { .no-print{display:none!important} }
    </style>

    @stack('styles')
</head>
<body>

<aside class="sidebar" id="sidebar">
    <div class="sidebar-logo">
        <div class="sidebar-logo-icon">S</div>
        <div class="sidebar-logo-text">
            <h2>SDN Dadap 4</h2>
            <p>Kabupaten Tangerang</p>
        </div>
    </div>

    <div class="sidebar-user">
        <div class="user-avatar">
            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
        </div>
        <div class="user-info-text">
            <h4>{{ Auth::user()->name }}</h4>
            <p>
                <span class="role-badge role-{{ Auth::user()->role }}">
                    {{ ucfirst(str_replace('_', ' ', Auth::user()->role)) }}
                </span>
            </p>
        </div>
    </div>

    <nav class="sidebar-nav">
        @php $role = Auth::user()->role; @endphp

        @if($role === 'admin')
            <div class="nav-section-title">Menu Utama</div>
            <a href="{{ route('admin.dashboard') }}"
               class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <span class="nav-icon">🏠</span> Dashboard
            </a>
            <div class="nav-section-title">Absensi</div>
            <a href="{{ route('admin.absen-guru.index') }}"
               class="nav-item {{ request()->routeIs('admin.absen-guru.*') ? 'active' : '' }}">
                <span class="nav-icon">👨‍🏫</span> Absensi Guru
            </a>
            <a href="{{ route('admin.absen-siswa.index') }}"
               class="nav-item {{ request()->routeIs('admin.absen-siswa.*') ? 'active' : '' }}">
                <span class="nav-icon">👨‍🎓</span> Absensi Siswa
            </a>
            <div class="nav-section-title">Data Master</div>
            <a href="{{ route('admin.guru.index') }}"
               class="nav-item {{ request()->routeIs('admin.guru.*') ? 'active' : '' }}">
                <span class="nav-icon">📋</span> Profil Guru
            </a>
            <a href="{{ route('admin.siswa.index') }}"
               class="nav-item {{ request()->routeIs('admin.siswa.*') ? 'active' : '' }}">
                <span class="nav-icon">📋</span> Profil Siswa
            </a>
            <div class="nav-section-title">Laporan</div>
            <a href="{{ route('admin.rekap.index') }}"
               class="nav-item {{ request()->routeIs('admin.rekap.*') ? 'active' : '' }}">
                <span class="nav-icon">📊</span> Rekap Absensi
            </a>

        @elseif($role === 'guru')
            <div class="nav-section-title">Menu Utama</div>
            <a href="{{ route('guru.dashboard') }}"
               class="nav-item {{ request()->routeIs('guru.dashboard') ? 'active' : '' }}">
                <span class="nav-icon">🏠</span> Dashboard
            </a>
            <div class="nav-section-title">Absensi</div>
            <a href="{{ route('guru.absenguru.index') }}"
               class="nav-item {{ request()->routeIs('guru.absenguru.*') ? 'active' : '' }}">
                <span class="nav-icon">✅</span> Absensi Guru
            </a>
            <a href="{{ route('guru.absensiswa.index') }}"
               class="nav-item {{ request()->routeIs('guru.absensiswa.*') ? 'active' : '' }}">
                <span class="nav-icon">👨‍🎓</span> Absensi Siswa
            </a>
            <div class="nav-section-title">Data Master</div>
            <a href="{{ route('guru.profilguru.index') }}"
               class="nav-item {{ request()->routeIs('guru.profilguru.*') ? 'active' : '' }}">
                <span class="nav-icon">📋</span> Profil Guru
            </a>
            <a href="{{ route('guru.profilsiswa.index') }}"
               class="nav-item {{ request()->routeIs('guru.profilsiswa.*') ? 'active' : '' }}">
                <span class="nav-icon">📋</span> Profil Siswa
            </a>


        @elseif($role === 'kepala_sekolah')
            <div class="nav-section-title">Menu Utama</div>
            <a href="{{ route('kepala-sekolah.dashboard') }}"
               class="nav-item {{ request()->routeIs('kepala-sekolah.dashboard') ? 'active' : '' }}">
                <span class="nav-icon">🏠</span> Dashboard
            </a>
            <div class="nav-section-title">Absensi</div>
            
            </a>
            <div class="nav-section-title">Data Master</div>
            <a href="{{ route('kepala-sekolah.guru.index') }}"
               class="nav-item {{ request()->routeIs('kepala-sekolah.guru.*') ? 'active' : '' }}">
                <span class="nav-icon">📋</span> Profil Guru
            </a>
            <a href="{{ route('kepala-sekolah.siswa.index') }}"
               class="nav-item {{ request()->routeIs('kepala-sekolah.siswa.*') ? 'active' : '' }}">
                <span class="nav-icon">📋</span> Profil Siswa
            </a>
            <div class="nav-section-title">Laporan</div>
            <a href="{{ route('kepala-sekolah.rekap.index') }}"
               class="nav-item {{ request()->routeIs('kepala-sekolah.rekap.*') ? 'active' : '' }}">
                <span class="nav-icon">📊</span> Rekap Absensi
            </a>
            
        @elseif($role === 'siswa')
            <div class="nav-section-title">Menu Utama</div>
            <a href="{{ route('siswa.dashboard') }}"
               class="nav-item {{ request()->routeIs('siswa.dashboard') ? 'active' : '' }}">
                <span class="nav-icon">🏠</span> Dashboard
            </a>
            <div class="nav-section-title">Informasi</div>
            <a href="{{ route('siswa.absensi.index') }}"
               class="nav-item {{ request()->routeIs('siswa.absensi.*') ? 'active' : '' }}">
                <span class="nav-icon">👨‍🎓</span> Absensi Saya
            </a>
        @endif
    </nav>

    <div class="sidebar-footer">
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="logout-btn">
                Keluar dari Sistem
            </button>
        </form>
    </div>
</aside>

<main class="main">
    <div class="topbar">
        <div>
            <div class="page-title">@yield('title', 'Dashboard')</div>
        </div>
        <div class="topbar-right">
            <span class="topbar-date" id="topbarDate"></span>
            <div class="user-avatar" style="width:34px;height:34px;font-size:13px;">
                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
            </div>
        </div>
    </div>

    <div class="content">
        @if(session('success'))
            <div style="background:#E8F5E9;border:1px solid #A5D6A7;border-radius:var(--radius);padding:0.75rem 1rem;margin-bottom:1.25rem;font-size:0.85rem;color:#2E7D32;display:flex;align-items:center;gap:8px;">
                ✅ {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div style="background:var(--red-pale);border:1px solid var(--red-mid);border-radius:var(--radius);padding:0.75rem 1rem;margin-bottom:1.25rem;font-size:0.85rem;color:var(--red);display:flex;align-items:center;gap:8px;">
                ❌ {{ session('error') }}
            </div>
        @endif

        @yield('content')
    </div>
</main>

<script>
    const days   = ['Minggu','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'];
    const months = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
    const now    = new Date();
    document.getElementById('topbarDate').textContent =
        days[now.getDay()] + ', ' + now.getDate() + ' ' + months[now.getMonth()] + ' ' + now.getFullYear();

    function openModal(id) {
        var el = document.getElementById(id);
        if (el) { el.style.display = 'flex'; }
    }
    function closeModal(id) {
        var el = document.getElementById(id);
        if (el) { el.style.display = 'none'; }
    }
    function showToast(msg) { alert(msg); }

    document.addEventListener('mousedown', function(e) {
        if (e.target && e.target.classList.contains('mo')) {
            e.target.style.display = 'none';
        }
    });
</script>

@stack('scripts')
</body>
</html>