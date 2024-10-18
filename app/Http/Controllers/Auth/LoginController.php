<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function loginForm(){
        return view('auth.login');
    }

    public function login(Request $request)
    {
        // Validasi input
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:8',
        ], [
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Email tidak valid.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Minimal 8 karakter.',
        ]);

        // Data login
        $credentials = $request->only('email', 'password');

        // Proses autentikasi
        if (Auth::attempt($credentials)) {
            // Jika berhasil login, redirect ke halaman dashboard
            return redirect(route('home'))->with('success', 'Login berhasil!');
        } else {
            // Jika gagal login, redirect kembali ke form login dengan pesan error
            return back()->withErrors([
                'email' => 'Email atau password salah',
            ])->withInput($request->only('email'));
        }
    }

    // Method untuk logout
    public function logout()
    {
        Auth::logout();
        return redirect(route('auth.login.form'))->with('success', 'Anda telah logout.');
    }
}
