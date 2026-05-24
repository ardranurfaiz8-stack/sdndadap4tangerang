<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Absensi Guru — SDN Dadap 4</title>
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');

    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

    :root {
      --red:    #c0392b;
      --red2:   #e53935;
      --green:  #27ae60;
      --green2: #2ecc71;
      --yellow: #f39c12;
      --g50:    #fafafa;
      --g100:   #f5f5f5;
      --g200:   #eeeeee;
      --g400:   #bdbdbd;
      --g600:   #757575;
      --g800:   #424242;
      --g900:   #212121;
      --white:  #ffffff;
      --sh:     0 2px 16px rgba(0,0,0,.08);
      --sh2:    0 4px 32px rgba(0,0,0,.12);
    }

    body {
      font-family: 'Plus Jakarta Sans', sans-serif;
      background: var(--g100);
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 24px 16px;
    }

    .wrap {
      width: 100%;
      max-width: 420px;
    }

    /* Header sekolah */
    .school-header {
      text-align: center;
      margin-bottom: 20px;
    }
    .school-badge {
      display: inline-block;
      background: var(--red);
      color: #fff;
      font-size: 11px;
      font-weight: 800;
      letter-spacing: 1px;
      padding: 5px 16px;
      border-radius: 20px;
      margin-bottom: 6px;
    }
    .school-name {
      font-size: 13px;
      color: var(--g600);
      font-weight: 500;
    }

    /* Card utama */
    .card {
      background: var(--white);
      border-radius: 16px;
      box-shadow: var(--sh2);
      overflow: hidden;
    }

    .card-top {
      background: linear-gradient(135deg, var(--red) 0%, #b71c1c 100%);
      padding: 24px 24px 20px;
      text-align: center;
      color: #fff;
    }

    .guru-avatar {
      width: 64px;
      height: 64px;
      border-radius: 50%;
      background: rgba(255,255,255,.25);
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 22px;
      font-weight: 800;
      color: #fff;
      margin: 0 auto 12px;
      border: 2px solid rgba(255,255,255,.4);
    }

    .guru-nama {
      font-size: 18px;
      font-weight: 800;
      margin-bottom: 4px;
    }

    .guru-mapel {
      font-size: 12.5px;
      opacity: .85;
      font-weight: 500;
      margin-bottom: 2px;
    }

    .guru-nip {
      font-size: 11px;
      opacity: .7;
      font-family: monospace;
      letter-spacing: .5px;
    }

    .card-body {
      padding: 24px;
    }

    /* Alert */
    .alert {
      border-radius: 10px;
      padding: 12px 16px;
      font-size: 13px;
      font-weight: 600;
      margin-bottom: 20px;
      display: flex;
      align-items: center;
      gap: 10px;
    }
    .alert-success {
      background: #e8f5e9;
      color: #1b5e20;
      border: 1px solid #c8e6c9;
    }
    .alert-error {
      background: #ffebee;
      color: #b71c1c;
      border: 1px solid #ffcdd2;
    }
    .alert-icon { font-size: 18px; flex-shrink: 0; }

    /* Sudah absen card */
    .sudah-absen {
      text-align: center;
      padding: 10px 0 6px;
    }
    .sudah-absen .icon-besar {
      font-size: 48px;
      margin-bottom: 10px;
    }
    .sudah-absen h3 {
      font-size: 16px;
      font-weight: 800;
      color: var(--green);
      margin-bottom: 6px;
    }
    .sudah-absen p {
      font-size: 13px;
      color: var(--g600);
      line-height: 1.6;
    }

    .info-row {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 10px 0;
      border-bottom: 1px solid var(--g200);
      font-size: 13px;
    }
    .info-row:last-child { border-bottom: none; }
    .info-label { color: var(--g600); font-weight: 500; }
    .info-value { font-weight: 700; color: var(--g900); }

    .badge-hadir {
      background: #e8f5e9;
      color: #1b5e20;
      padding: 3px 10px;
      border-radius: 20px;
      font-size: 12px;
      font-weight: 700;
    }

    /* Belum absen */
    .belum-absen {
      text-align: center;
    }
    .belum-absen .icon-besar {
      font-size: 48px;
      margin-bottom: 10px;
    }
    .belum-absen h3 {
      font-size: 16px;
      font-weight: 800;
      color: var(--g800);
      margin-bottom: 6px;
    }
    .belum-absen p {
      font-size: 13px;
      color: var(--g600);
      margin-bottom: 20px;
      line-height: 1.6;
    }

    .info-box {
      background: var(--g50);
      border: 1px solid var(--g200);
      border-radius: 10px;
      padding: 14px 16px;
      margin-bottom: 20px;
      text-align: left;
    }

    .btn-hadir {
      display: block;
      width: 100%;
      background: linear-gradient(135deg, var(--green) 0%, #1b5e20 100%);
      color: #fff;
      border: none;
      border-radius: 10px;
      padding: 14px;
      font-size: 15px;
      font-weight: 800;
      font-family: inherit;
      cursor: pointer;
      transition: opacity .2s, transform .1s;
      letter-spacing: .3px;
    }
    .btn-hadir:hover  { opacity: .92; }
    .btn-hadir:active { transform: scale(.98); }

    .waktu-sekarang {
      text-align: center;
      font-size: 11.5px;
      color: var(--g400);
      margin-top: 14px;
    }

    /* Footer */
    .footer {
      text-align: center;
      margin-top: 16px;
      font-size: 11.5px;
      color: var(--g400);
    }
  </style>
</head>
<body>
  <div class="wrap">

    {{-- Header --}}
    <div class="school-header">
      <div class="school-badge">SDN DADAP 4</div>
      <div class="school-name">Sistem Absensi Digital • Kabupaten Tangerang</div>
    </div>

    <div class="card">
      {{-- Info Guru --}}
      <div class="card-top">
        <div class="guru-avatar">
          {{ strtoupper(collect(explode(' ', $guru->nama))->take(2)->map(fn($w) => $w[0])->join('')) }}
        </div>
        <div class="guru-nama">{{ $guru->nama }}</div>
        <div class="guru-mapel">{{ $guru->mata_pelajaran ?? 'Guru' }}</div>
        <div class="guru-nip">NIP: {{ $guru->nip }}</div>
      </div>

      <div class="card-body">

        {{-- Alert sukses --}}
        @if(session('success'))
          <div class="alert alert-success">
            <span class="alert-icon">✅</span>
            {{ session('success') }}
          </div>
        @endif

        {{-- Alert error --}}
        @if(session('error'))
          <div class="alert alert-error">
            <span class="alert-icon">⚠️</span>
            {{ session('error') }}
          </div>
        @endif

        {{-- Sudah absen hari ini --}}
        @if($sudahAbsen)
          <div class="sudah-absen">
            <div class="icon-besar">✅</div>
            <h3>Sudah Absen Hari Ini</h3>
            <p>Absensi Anda sudah tercatat.<br>Terima kasih!</p>
          </div>

          @if($dataAbsen)
            <div class="info-box" style="margin-top:16px;">
              <div class="info-row">
                <span class="info-label">Tanggal</span>
                <span class="info-value">{{ \Carbon\Carbon::parse($dataAbsen->tanggal)->translatedFormat('d F Y') }}</span>
              </div>
              <div class="info-row">
                <span class="info-label">Jam Masuk</span>
                <span class="info-value">{{ $dataAbsen->jam_masuk ?? '-' }}</span>
              </div>
              <div class="info-row">
                <span class="info-label">Status</span>
                <span class="badge-hadir">Hadir</span>
              </div>
            </div>
          @endif

        {{-- Belum absen --}}
        @else
          <div class="belum-absen">
            <div class="icon-besar">📋</div>
            <h3>Konfirmasi Kehadiran</h3>
            <p>Tekan tombol di bawah untuk mencatat kehadiran Anda hari ini.</p>
          </div>

          <div class="info-box">
            <div class="info-row">
              <span class="info-label">Tanggal</span>
              <span class="info-value">{{ now()->translatedFormat('d F Y') }}</span>
            </div>
            <div class="info-row">
              <span class="info-label">Hari</span>
              <span class="info-value">{{ now()->translatedFormat('l') }}</span>
            </div>
            <div class="info-row" style="border:none;">
              <span class="info-label">Waktu Scan</span>
              <span class="info-value" id="jam-sekarang">--:--</span>
            </div>
          </div>

          <form method="POST" action="{{ route('absen.submit', $guru->nip) }}">
            @csrf
            <button type="submit" class="btn-hadir">
              ✅ Konfirmasi Hadir Sekarang
            </button>
          </form>

          <div class="waktu-sekarang">
            Absensi hanya bisa dilakukan <strong>1 kali per hari</strong>
          </div>
        @endif

      </div>
    </div>

    <div class="footer">
      SDN Dadap 4 &mdash; Sistem Absensi Digital &copy; {{ date('Y') }}
    </div>

  </div>

  <script>
    // Tampilkan jam real-time
    function updateJam() {
      const el = document.getElementById('jam-sekarang');
      if (!el) return;
      const now = new Date();
      const h = String(now.getHours()).padStart(2, '0');
      const m = String(now.getMinutes()).padStart(2, '0');
      const s = String(now.getSeconds()).padStart(2, '0');
      el.textContent = h + ':' + m + ':' + s;
    }
    updateJam();
    setInterval(updateJam, 1000);
  </script>
</body>
</html>