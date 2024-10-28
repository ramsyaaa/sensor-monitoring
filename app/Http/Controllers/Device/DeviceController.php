<?php

namespace App\Http\Controllers\Device;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class DeviceController extends Controller
{
    public function index()
    {
        $token = session('access_token'); // Ambil token dari session

        if (!$token) {
            return redirect()->route('login')->withErrors('Token tidak ditemukan atau sudah kedaluwarsa.');
        }

        // Menyiapkan data JSON untuk dikirim
        $jsonData = [
            'userId' => 99837,
            'currPage' => 1,
            'pageSize' => 10,
        ];

        // Mengirim permintaan POST ke API dengan JSON di body
        $response = Http::timeout(20)->withoutVerifying()
            ->withHeaders([
                'Authorization' => 'Bearer ' . $token,
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ])
            ->post(env('URL_API') . '/api/v1/get-device', $jsonData);

        // Cek apakah response berhasil
        if ($response->successful()) {
            $data = $response->json(); // Mengambil data dari response
            $data = $data['data'];
            return view('device.index', compact('data'));
        } else {
            return back()->withErrors('Gagal mengambil data dari API: ' . $response->body());
        }
    }






    public function show($id){
        $token = session('access_token'); // Ambil token dari session

        if (!$token) {
            return redirect()->route('login')->withErrors('Token tidak ditemukan atau sudah kedaluwarsa.');
        }

        // Menyiapkan data JSON untuk dikirim
        $jsonData = [
            'userId' => 99837,
            "deviceId" => intval($id),
            'currPage' => 1,
            'pageSize' => 10,
        ];

        // Mengirim permintaan POST ke API dengan JSON di body
        $response = Http::timeout(20)->withoutVerifying()
            ->withHeaders([
                'Authorization' => 'Bearer ' . $token,
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ])
            ->post(env('URL_API') . '/api/v1/get-single-device', $jsonData);

        // Cek apakah response berhasil
        if ($response->successful()) {
            $data = $response->json(); // Mengambil data dari response
            $data = $data['data'];
            $sensors = $data['device']['sensorsList'];
            // dd($sensors);
            return view('device.show', compact('data', 'sensors'));
        } else {
            dd($response);
            return back()->withErrors('Gagal mengambil data dari API: ' . $response->body());
        }
        return view('device.show');
    }

    public function create(){
        return view('device.create');
    }

    public function edit($id){
        return view('device.edit');
    }
}
