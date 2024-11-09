<?php

namespace App\Http\Controllers\Map;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class MapController extends Controller
{
    public function index(){
        $token = session('access_token'); // Ambil token dari session

        if (!$token) {
            return redirect()->route('login')->withErrors('Token tidak ditemukan atau sudah kedaluwarsa.');
        }

        // Mengirim permintaan POST ke API dengan JSON di body
        $response = Http::timeout(20)->withoutVerifying()
            ->withHeaders([
                'Authorization' => 'Bearer ' . $token,
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ])
            ->get(env('URL_API') . '/api/v1/geomapping/device-list');

        // Cek apakah response berhasil
        if ($response->successful()) {
            $data = $response->json(); // Mengambil data dari response
            $data = $data['data'];
            return view('maps.index', compact('data'));
        } else {
            dd($response->body());
            return back()->withErrors('Gagal mengambil data dari API: ' . $response->body());
        }
    }
}
