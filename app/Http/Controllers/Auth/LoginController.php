<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class LoginController extends Controller
{
    public function loginForm(){
        return view('auth.login');
    }

    public function login(Request $request)
    {
        // Validasi input
        $request->validate([
            'username' => 'required',
            'password' => 'required|min:8',
        ], [
            'username.required' => 'Username wajib diisi.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal 8 karakter.',
        ]);

        // Data login dalam format JSON
        $credentials = [
            'username' => $request->username,
            'password' => $request->password,
        ];

        // Kirim permintaan ke API
        $response = Http::timeout(20)->withoutVerifying()->post('https://sensor-monitoring.apicollection.my.id/api/v1/authenticate', $credentials);

        // Cek apakah respons sukses
        if ($response->ok()) {
            // Ambil data dari respons
            $data = $response->json()['data'];

            // Simpan token dan data pengguna di session
            session([
                'access_token' => $data['access_token'],
                'clientId' => $data['clientId'],
                'clientSecret' => $data['clientSecret'],
                'expires_in' => $data['expires_in'],
                'refresh_token' => $data['refresh_token'],
                'scope' => $data['scope'],
                'userId' => $data['userId'],
            ]);

            // Redirect ke halaman dashboard dengan pesan sukses
            return redirect(route('home'))->with('success', 'Login berhasil!');
        } else {
            // Jika gagal login, redirect kembali ke form login dengan pesan error
            return back()->withErrors([
                'username' => 'Username atau password salah',
            ])->withInput($request->only('username'));
        }
    }


    // Method untuk logout
    public function logout()
    {
        Auth::logout();
        return redirect(route('auth.login.form'))->with('success', 'Anda telah logout.');
    }
}
