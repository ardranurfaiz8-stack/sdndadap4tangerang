<?php

namespace App\Http\Controllers\guru;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

/**
 * ProfilController
 * Menangani profil akun pengguna yang sedang login
 * (ubah nama, email, password).
 */
class ProfilController extends Controller
{
    /**
     * Tampilkan halaman profil akun.
     * GET /guru/profil-akun
     */
    public function index()
    {
        $user = Auth::user();
        return view('guru.profil_akun', compact('user'));
    }

    /**
     * Update nama dan email.
     * POST /guru/profil-akun/update
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name'  => 'required|string|max:100',
            'email' => 'required|email|unique:users,email,'.$user->id,
        ]);

        $user->update([
            'name'  => $request->name,
            'email' => $request->email,
        ]);

        return back()->with('success', 'Profil berhasil diperbarui! ✅');
    }

    /**
     * Update password.
     * POST /guru/profil-akun/password
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'password_lama' => 'required|string',
            'password'      => 'required|string|min:8|confirmed',
        ], [
            'password.min'       => 'Password baru minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);

        $user = Auth::user();

        if (!Hash::check($request->password_lama, $user->password)) {
            return back()->withErrors(['password_lama' => 'Password lama tidak sesuai.']);
        }

        $user->update(['password' => Hash::make($request->password)]);

        return back()->with('success', 'Password berhasil diubah! 🔐');
    }
}