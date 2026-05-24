# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

Sistem Informasi Absensi Sekolah for **SDN Dadap 4, Kabupaten Tangerang**. A school attendance management system built with Laravel 13 (PHP 8.3). Tracks daily attendance for both teachers (guru) and students (siswa), with QR code scanning support and monthly recap reports.

## Commands

```bash
# Full project setup (install deps, generate key, migrate, build assets)
composer setup

# Development server (runs Laravel server + queue + Pail logs + Vite concurrently)
composer dev

# Run tests
composer test                    # full suite
php artisan test --filter=SomeName  # single test

# Linting (Laravel Pint - PSR-12 style)
vendor/bin/pint                  # fix all
vendor/bin/pint --test           # dry run

# Database
php artisan migrate              # run migrations
php artisan migrate:fresh --seed # reset & seed

# Frontend assets
npm run dev                      # Vite dev server with HMR
npm run build                    # production build
```

## Architecture

### Tech Stack
- **Backend:** Laravel 13, PHP 8.3, MySQL (DB: `sistem_informasi_absensi_sekolah`)
- **Frontend:** Blade templates, Bootstrap 5.3 (CDN), Chart.js (CDN), Tailwind CSS 4 (via Vite)
- **Layout:** Single layout `resources/views/layouts/app.blade.php` with inline CSS (no separate stylesheet). Sidebar navigation is role-conditional within the layout itself, not a separate component.
- **Build:** Vite 8 + `laravel-vite-plugin` + `@tailwindcss/vite`

### Authentication & Roles
Single `users` table with a `role` enum column. Four roles: `admin`, `guru`, `kepala_sekolah`, `siswa`. Login uses a single form where the user selects their role; the controller validates that the selected role matches the user's stored role. Auth is standard Laravel session guard (`auth`/`guest` middleware), no custom middleware.

All roles currently redirect to `guru.dashboard` after login. Role-based sidebar rendering happens in `layouts/app.blade.php` via `@if($role === '...')` blocks.

### Routing
All routes are in `routes/web.php` (single file). Currently only guru-prefixed routes are fully defined (`/guru/*`). Admin, kepala_sekolah, and siswa routes are referenced in the sidebar blade but may not all be registered in web.php yet.

Route naming convention: `{role}.{resource}.{action}` (e.g., `guru.absen_guru.store`, `admin.rekap.index`).

### Controllers
Organized by role under `app/Http/Controllers/{role}/`:
- `guru/` — LoginController, DashboardController, AbsenguruController, AbsensiswaController, ProfilguruController, ProfilsiswaController, RekapController, ProfilController, AbsenController
- `admin/` — DashboardController, AbsenGuruController, AbsensiswaController, GuruController, SiswaController, RekapController, ProfilController
- `kepala_sekolah/` — LoginController, DashboardController, RekapController, LaporanController
- `siswa/` — DashboardController, AbsensiController
- `auth/` — LoginController, LogoutController

`AbsenController` (guru/) serves as a unified QR scan endpoint handling both guru and siswa attendance via `POST /guru/scan-qr`.

### Models & Database Schema
Key models in `app/Models/`:

| Model | Table | Key Fields |
|-------|-------|------------|
| `User` | `users` | name, username, email, password, role |
| `Guru` | `gurus` | user_id (FK), nama, nip, mata_pelajaran, foto |
| `Siswa` | `siswas` | user_id (FK), nama, nis, kelas, nama_orang_tua, foto |
| `AbsenGuru` | `absen_gurus` | guru_id (FK), tanggal, status, jam_masuk, jam_keluar |
| `AbsenSiswa` | `absen_siswas` | siswa_id (FK), tanggal, status, dicatat_oleh (guru FK) |
| `RekapAbsenGuru` | `rekap_absen_gurus` | guru_id, bulan, tahun, total_hadir/sakit/izin/alpha, persentase_kehadiran |
| `RekapAbsenSiswa` | `rekap_absen_siswas` | siswa_id, bulan, tahun, total_hadir/sakit/izin/alpha, persentase_kehadiran |
| `ProfilGuru` | `profil_gurus` | guru_id (FK) |
| `ProfilSiswa` | `profil_siswas` | siswa_id (FK) |

Attendance status values: `hadir`, `sakit`, `izin`, `alpha`. Both rekap models have a static `generate()` method that computes monthly summaries from daily attendance records via `updateOrCreate`.

### Views
Blade templates in `resources/views/`:
- `layouts/app.blade.php` — main layout with sidebar, topbar, flash messages
- `auth/login.blade.php` — unified login form
- `guru/` — guru-facing pages (dashboard, absen_guru, absen_siswa, profil_*, Rekap)
- `admin/` — admin pages (dashboard, rekap)
- `siswa/` — student pages (dashboard, absensi)
- `kepala_sekolah/` — principal pages (Dashboard, Rekap, Laporan, Guru_Rekap)
- `data_absen/`, `absen_siswa/`, `profil_guru/`, `profil_siswa/` — CRUD form views (index/create/edit/show)

### Language
UI text, validation messages, variable names, and database columns are in **Bahasa Indonesia**. Keep this convention when adding new code.
