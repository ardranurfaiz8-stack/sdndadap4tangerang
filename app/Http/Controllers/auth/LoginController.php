<?php

namespace App\Http\Controllers\auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class LoginController extends Controller
{
    public function showForm()
    {
        if (Auth::check()) {
            return $this->redirectByRole(Auth::user()->role);
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
            'role'     => 'required|in:admin,guru,kepala_sekolah,siswa',
        ], [
            'username.required' => 'Username atau email wajib diisi.',
            'password.required' => 'Password wajib diisi.',
            'role.required'     => 'Pilih role login terlebih dahulu.',
        ]);

        $input = trim($request->username);
        $role  = $request->role;

        // Cek apakah kolom 'username' sudah ada di tabel users
        $hasUsernameColumn = Schema::hasColumn('users', 'username');

        $user = User::where('role', $role)
            ->where(function ($query) use ($input, $hasUsernameColumn) {
                if ($hasUsernameColumn) {
                    // Kolom username ada: cari by username ATAU email
                    $query->where('username', $input)
                          ->orWhere('email', $input);
                } else {
                    // Kolom username belum ada: cari by name ATAU email saja
                    $query->where('email', $input)
                          ->orWhere('name', $input);
                }
            })
            ->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return back()
                ->withErrors(['username' => 'Email/username atau password salah, atau role tidak sesuai.'])
                ->withInput($request->only('username', 'role'));
        }

        Auth::login($user, $request->boolean('remember'));
        $request->session()->regenerate();

        return $this->redirectByRole($user->role);
    }

    public function showForgot()
    {
        return view('auth.forgot');
    }

    public function sendResetLink(Request $request)
    {
        $request->validate(['email' => 'required|email']);
        return back()->with('status', 'Link reset password telah dikirim ke email Anda.');
    }

    private function redirectByRole(string $role)
    {
        return match ($role) {
            'admin'          => redirect()->route('admin.dashboard'),
            'guru'           => redirect()->route('guru.dashboard'),
            'siswa'          => redirect()->route('siswa.dashboard'),
            'kepala_sekolah' => redirect()->route('kepala-sekolah.dashboard'),
            default          => redirect('/'),
        };
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}