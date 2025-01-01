<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ProfileController extends Controller
{
    public function index()
    {
        $role = session('role');
        if($role != 'visitor'){
            return redirect()->route('dashboard');
        }
        return view('profile.index');
    }

    public function update(Request $request){
        $role = session('role');
        if($role != 'visitor'){
            return redirect()->route('dashboard');
        }

        $userId = session('userId');

        $token = session('access_token'); // Ambil token dari session

        if (!$token) {
            return redirect()->route('login')->withErrors('Token tidak ditemukan atau sudah kedaluwarsa.');
        }

        // Menyiapkan data JSON untuk dikirim
        $jsonData = [
            "password" => $request->password,
        ];

        // Mengirim permintaan POST ke API dengan JSON di body
        $response = Http::timeout(20)->withoutVerifying()
            ->withHeaders([
                'Authorization' => 'Bearer ' . $token,
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ])
            ->put(env('URL_API') . '/api/v1/user/edit/' . $userId, $jsonData);

        // Cek apakah response berhasil
        if ($response->successful()) {
            return redirect()->route('profile.index')->with('success', 'Data berhasil diubah.');
        } else {
            return back()->withErrors('Gagal mengambil data dari API: ' . $response->body());
        }        
    }
}
