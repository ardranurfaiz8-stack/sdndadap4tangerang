<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes — SDN Dadap 4
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    if (!auth()->check()) return redirect()->route('login');
    return match (auth()->user()->role) {
        'admin'          => redirect()->route('admin.dashboard'),
        'kepala_sekolah' => redirect()->route('kepala-sekolah.dashboard'),
        'siswa'          => redirect()->route('siswa.dashboard'),
        default          => redirect()->route('guru.dashboard'),
    };
});

// ══════════════════════════════════════
// AUTH
// ══════════════════════════════════════
Route::middleware('guest')->group(function () {
    Route::get ('/login', [\App\Http\Controllers\guru\LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [\App\Http\Controllers\guru\LoginController::class, 'login'])->name('login.post');
});
Route::post('/logout', [\App\Http\Controllers\guru\LoginController::class, 'logout'])->name('logout')->middleware('auth');

// ══════════════════════════════════════
// ADMIN
// ══════════════════════════════════════
Route::middleware('auth')->prefix('admin')->name('admin.')->group(function () {
    Route::get('dashboard', [\App\Http\Controllers\admin\DashboardController::class, 'index'])->name('dashboard');

    Route::resource('absen-guru', \App\Http\Controllers\admin\AbsenGuruController::class)->parameters(['absen-guru' => 'absenGuru']);
    Route::resource('absen-siswa', \App\Http\Controllers\admin\AbsensiswaController::class)->parameters(['absen-siswa' => 'absenSiswa']);
    Route::resource('guru', \App\Http\Controllers\admin\GuruController::class);
    Route::put('guru/{guru}/profil', [\App\Http\Controllers\admin\ProfilController::class, 'update'])->name('guru.profil.update');
    Route::resource('siswa', \App\Http\Controllers\admin\SiswaController::class);

    Route::get('profil', [\App\Http\Controllers\admin\ProfilController::class, 'index'])->name('profil.index');
    Route::get('rekap', [\App\Http\Controllers\admin\RekapController::class, 'index'])->name('rekap.index');
    Route::get('rekap/export', [\App\Http\Controllers\admin\RekapController::class, 'export'])->name('rekap.export');
});

// ══════════════════════════════════════
// GURU
// ══════════════════════════════════════
Route::middleware('auth')->prefix('guru')->name('guru.')->group(function () {
    Route::get('dashboard', [\App\Http\Controllers\guru\DashboardController::class, 'index'])->name('dashboard');

    // Absensi Guru
    Route::get   ('absen_guru',             [\App\Http\Controllers\guru\AbsenguruController::class, 'index'])  ->name('absen_guru.index');
    Route::get   ('absen_guru/create',      [\App\Http\Controllers\guru\AbsenguruController::class, 'create']) ->name('absen_guru.create');
    Route::post  ('absen_guru',             [\App\Http\Controllers\guru\AbsenguruController::class, 'store'])  ->name('absen_guru.store');
    Route::get   ('absen_guru/{id}/edit',   [\App\Http\Controllers\guru\AbsenguruController::class, 'edit'])   ->name('absen_guru.edit');
    Route::put   ('absen_guru/{id}',        [\App\Http\Controllers\guru\AbsenguruController::class, 'update']) ->name('absen_guru.update');
    Route::delete('absen_guru/{id}',        [\App\Http\Controllers\guru\AbsenguruController::class, 'destroy'])->name('absen_guru.destroy');
    Route::post  ('absen_guru/scan',        [\App\Http\Controllers\guru\AbsenguruController::class, 'scanQR']) ->name('absen_guru.scan');
    
    // Absensi Siswa
    Route::get   ('absen_siswa',              [\App\Http\Controllers\guru\AbsensiswaController::class, 'index'])       ->name('absen_siswa.index');
    Route::get   ('absen_siswa/create',       [\App\Http\Controllers\guru\AbsensiswaController::class, 'create'])      ->name('absen_siswa.create');
    Route::post  ('absen_siswa',              [\App\Http\Controllers\guru\AbsensiswaController::class, 'store'])       ->name('absen_siswa.store');
    Route::get   ('absen_siswa/{id}/edit',    [\App\Http\Controllers\guru\AbsensiswaController::class, 'edit'])        ->name('absen_siswa.edit');
    Route::put   ('absen_siswa/{id}',         [\App\Http\Controllers\guru\AbsensiswaController::class, 'update'])      ->name('absen_siswa.update');
    Route::delete('absen_siswa/{id}',         [\App\Http\Controllers\guru\AbsensiswaController::class, 'destroy'])     ->name('absen_siswa.destroy');
    Route::post  ('absen_siswa/scan',         [\App\Http\Controllers\guru\AbsensiswaController::class, 'scanQR'])      ->name('absen_siswa.scan');
    Route::get   ('api/siswa-by-kelas',       [\App\Http\Controllers\guru\AbsensiswaController::class, 'siswaByKelas'])->name('api.siswa-by-kelas');
    
    // Alias sidebar
    Route::get('absenguru',  [\App\Http\Controllers\guru\AbsenguruController::class, 'index'])->name('absenguru.index');
    Route::get('absensiswa', [\App\Http\Controllers\guru\AbsensiswaController::class, 'index'])->name('absensiswa.index');
    Route::get('profilguru', [\App\Http\Controllers\guru\ProfilguruController::class, 'index'])->name('profilguru.index');
    Route::get('profilsiswa',[\App\Http\Controllers\guru\ProfilsiswaController::class, 'index'])->name('profilsiswa.index');

    // Profil Guru CRUD
    Route::get   ('profil_guru',             [\App\Http\Controllers\guru\ProfilguruController::class, 'index'])  ->name('profil_guru.index');
    Route::get   ('profil_guru/create',      [\App\Http\Controllers\guru\ProfilguruController::class, 'create'])->name('profil_guru.create');
    Route::post  ('profil_guru',             [\App\Http\Controllers\guru\ProfilguruController::class, 'store'])  ->name('profil_guru.store');
    Route::get   ('profil_guru/{id}/edit',   [\App\Http\Controllers\guru\ProfilguruController::class, 'edit'])  ->name('profil_guru.edit');
    Route::put   ('profil_guru/{id}',        [\App\Http\Controllers\guru\ProfilguruController::class, 'update'])->name('profil_guru.update');
    Route::delete('profil_guru/{id}',        [\App\Http\Controllers\guru\ProfilguruController::class, 'destroy'])->name('profil_guru.destroy');
  
    // Profil Siswa CRUD
    Route::get   ('profil_siswa',            [\App\Http\Controllers\guru\ProfilsiswaController::class, 'index'])  ->name('profil_siswa.index');
    Route::get   ('profil_siswa/create',     [\App\Http\Controllers\guru\ProfilsiswaController::class, 'create'])->name('profil_siswa.create');
    Route::post  ('profil_siswa',            [\App\Http\Controllers\guru\ProfilsiswaController::class, 'store'])  ->name('profil_siswa.store');
    Route::get   ('profil_siswa/{id}/edit',  [\App\Http\Controllers\guru\ProfilsiswaController::class, 'edit'])  ->name('profil_siswa.edit');
    Route::put   ('profil_siswa/{id}',       [\App\Http\Controllers\guru\ProfilsiswaController::class, 'update'])->name('profil_siswa.update');
    Route::delete('profil_siswa/{id}',       [\App\Http\Controllers\guru\ProfilsiswaController::class, 'destroy'])->name('profil_siswa.destroy');
   
    // Rekap
    Route::get('rekap',       [\App\Http\Controllers\guru\RekapController::class, 'index'])->name('rekap.index');
    Route::get('rekap/cetak', [\App\Http\Controllers\guru\RekapController::class, 'cetak'])->name('rekap.cetak');

    // Profil Akun
    Route::get ('profil-akun',          [\App\Http\Controllers\guru\ProfilController::class, 'index'])         ->name('profil.index');
    Route::post('profil-akun/update',   [\App\Http\Controllers\guru\ProfilController::class, 'update'])         ->name('profil.update');
    Route::post('profil-akun/password', [\App\Http\Controllers\guru\ProfilController::class, 'updatePassword']) ->name('profil.password');

    // API
    Route::post('scan-qr',           [\App\Http\Controllers\guru\AbsenController::class, 'scanQR'])       ->name('scan.qr');
    Route::get ('api/rekap-hari-ini', [\App\Http\Controllers\guru\AbsenController::class, 'rekapHariIni'])->name('api.rekap-hari-ini');
});

// ══════════════════════════════════════
// KEPALA SEKOLAH (hanya lihat)
// ══════════════════════════════════════
Route::middleware('auth')->prefix('kepala-sekolah')->name('kepala-sekolah.')->group(function () {
    Route::get('dashboard',  [\App\Http\Controllers\kepala_sekolah\DashboardController::class, 'index'])->name('dashboard');
    Route::get('absen-guru', [\App\Http\Controllers\kepala_sekolah\AbsenGuruController::class, 'index'])->name('absen-guru.index');
    Route::get('absen-siswa',[\App\Http\Controllers\kepala_sekolah\AbsenSiswaController::class, 'index'])->name('absen-siswa.index');
    Route::get('guru',       [\App\Http\Controllers\kepala_sekolah\GuruController::class, 'index'])->name('guru.index');
    Route::get('siswa',      [\App\Http\Controllers\kepala_sekolah\SiswaController::class, 'index'])->name('siswa.index');
    Route::get('rekap',      [\App\Http\Controllers\kepala_sekolah\RekapController::class, 'index'])->name('rekap.index');
    Route::get('laporan',    [\App\Http\Controllers\kepala_sekolah\LaporanController::class, 'index'])->name('laporan.index');
    Route::get('laporan/export', [\App\Http\Controllers\kepala_sekolah\LaporanController::class, 'export'])->name('laporan.export');
});

// ══════════════════════════════════════
// SISWA (hanya lihat)
// ══════════════════════════════════════
Route::middleware('auth')->prefix('siswa')->name('siswa.')->group(function () {
    Route::get('dashboard', [\App\Http\Controllers\siswa\DashboardController::class, 'index'])->name('dashboard');
    Route::get('absensi',   [\App\Http\Controllers\siswa\AbsensiController::class, 'index'])  ->name('absensi.index');
});

// ══════════════════════════════════════
// SCAN QR ABSEN GURU (public - tanpa login)
// ══════════════════════════════════════
// SCAN QR ABSEN GURU (public - tanpa login)
Route::get('/absen/scan/{nip}',  [\App\Http\Controllers\AbsenScanController::class, 'showForm'])->name('absen.scan');
Route::post('/absen/scan/{nip}', [\App\Http\Controllers\AbsenScanController::class, 'submitForm'])->name('absen.submit');