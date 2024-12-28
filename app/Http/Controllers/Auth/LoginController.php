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
            'password' => 'required',
        ], [
            'username.required' => 'Username wajib diisi.',
            'password.required' => 'Password wajib diisi.',
        ]);

        // Data login dalam format JSON
        $credentials = [
            'username' => $request->username,
            'password' => $request->password,
        ];

        // Kirim permintaan ke API
        $response = Http::timeout(20)->withoutVerifying()
            ->post(env('URL_API') . '/api/v1/authenticate', $credentials);

        // Cek apakah respons sukses
        if ($response->ok() && isset($response->json()['data'])) {
            $data = $response->json()['data'];

            $role = $data['role'];

            // Simpan token dan data pengguna di session dengan timestamp
            session([
                'access_token' => $data['access_token'],
                'clientId' => $data['clientId'],
                'clientSecret' => $data['clientSecret'],
                'expires_in' => $data['expires_in'],
                'refresh_token' => $data['refresh_token'],
                'scope' => $data['scope'],
                'userId' => $data['userId'],
                'role' => $role,
                'login_time' => now(), // Menyimpan waktu login
            ]);

            return redirect(route('home'))->with('success', 'Login berhasil!');
        } else {
            return back()->withErrors([
                'username' => 'Username atau password salah',
            ])->withInput($request->only('username'));
        }
    }



    // Method untuk logout
    public function logout()
    {
        // Hapus semua data session yang terkait dengan autentikasi
        session()->forget([
            'access_token',
            'clientId',
            'clientSecret',
            'expires_in',
            'refresh_token',
            'scope',
            'userId',
            'login_time'
        ]);

        // Hapus seluruh session atau bisa gunakan session()->flush() jika ingin membersihkan semua
        session()->flush();

        // Arahkan ke halaman login
        return redirect()->route('auth.login.form')->with('success', 'Anda telah berhasil logout.');
    }
}
