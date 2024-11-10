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
            $sensorList = [];
            foreach ($data as $index => $item) {
                $jsonData = [
                    "deviceID" => $item['id'],
                ];
        
                // Mengirim permintaan POST ke API dengan JSON di body
                $response = Http::timeout(20)->withoutVerifying()
                    ->withHeaders([
                        'Authorization' => 'Bearer ' . $token,
                        'Accept' => 'application/json',
                        'Content-Type' => 'application/json',
                    ])
                    ->post(env('URL_API') . '/api/v1/geomapping/sensor-list', $jsonData);

                if ($response->successful()) {
                    $sensors = $response->json();
                    $sensors = $sensors['data'];

                    foreach ($sensors as $key => $item1) {
                        if($item1['lat'] != null && $item1['lng'] != null){
                            $sensorList[] = $item1;
                        }
                    }
                }
            }
            $devices = array_map(function($device) {
                $device['source'] = 'data';
                return $device;
            }, $data);
            
            $data_list_devices = array_map(function($device) {
                $device['source'] = 'data_list';
                return $device;
            }, $sensorList);
            
            // Gabungkan kedua array
            $data = [];
            $data['allDevices'] = array_merge($devices, $data_list_devices);
            return view('maps.index', $data);
        } else {
            return back()->withErrors('Gagal mengambil data dari API: ' . $response->body());
        }
    }
}
