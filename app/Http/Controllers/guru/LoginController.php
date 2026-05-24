<?php

namespace App\Http\Controllers\guru;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string|min:6',
            'role'     => 'required|string',
        ], [
            'username.required' => 'Username wajib diisi.',
            'password.required' => 'Password wajib diisi.',
            'password.min'      => 'Password minimal 6 karakter.',
            'role.required'     => 'Pilih role terlebih dahulu.',
        ]);

        $username = $request->username;
        $password = $request->password;
        $role     = $request->role;

        // Coba login dengan email atau username
        $fieldEmail    = ['email'    => $username, 'password' => $password];
        $fieldUsername = ['username' => $username, 'password' => $password];

        $loggedIn = Auth::attempt($fieldEmail, $request->boolean('remember'))
                 || Auth::attempt($fieldUsername, $request->boolean('remember'));

        if (!$loggedIn) {
            return back()
                ->withInput($request->only('username', 'role'))
                ->withErrors(['username' => 'Username/email atau password salah.']);
        }

        $user = Auth::user();

        // Cek role cocok
        if (($user->role ?? '') !== $role) {
            Auth::logout();
            return back()
                ->withInput($request->only('username', 'role'))
                ->withErrors(['role' => 'Role yang dipilih tidak sesuai dengan akun ini.']);
        }

        $request->session()->regenerate();

        $redirectRoute = match($role) {
            'admin'          => 'admin.dashboard',
            'kepala_sekolah' => 'kepala-sekolah.dashboard',
            'siswa'          => 'siswa.dashboard',
            default          => 'guru.dashboard',
        };

        return redirect()->route($redirectRoute)
            ->with('success', 'Selamat datang, '.$user->name.'! 👋');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login')
            ->with('success', 'Anda berhasil keluar. Sampai jumpa! 👋');
    }
}