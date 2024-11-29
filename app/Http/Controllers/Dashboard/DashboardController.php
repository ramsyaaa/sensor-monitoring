<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class DashboardController extends Controller
{
    public function index()
    {
        $token = session('access_token'); // Ambil token dari session

        if (!$token) {
            return redirect()->route('login')->withErrors('Token tidak ditemukan atau sudah kedaluwarsa.');
        }

        $response = Http::timeout(20)->withoutVerifying()
            ->withHeaders([
                'Authorization' => 'Bearer ' . $token,
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ])
            ->get(env('URL_API') . '/api/v1/geomapping/dashboard');

        // Cek apakah response berhasil
        if ($response->successful()) {
            $responseJson = $response->json(); // Mengambil data dari response
            $response_data = $responseJson['data'];

            $allDevice = [];
            foreach ($response_data as $index => $groups) {
                foreach ($groups as $index2 => $group) {
                    $allDevice[$index2] = [];
                    foreach ($group as $index3 => $device) {
                        if(isset($device['sensor_id']) && $device['sensor_id'] != null && $device['is_line'] == 1){
                            $jsonData = [
                                'userId' => 99837,
                                'sensorId' => intval($device['sensor_id']),
                            ];
        
                            $response = Http::timeout(20)->withoutVerifying()
                                ->withHeaders([
                                    'Authorization' => 'Bearer ' . $token,
                                    'Accept' => 'application/json',
                                    'Content-Type' => 'application/json',
                                ])
                                ->post(env('URL_API') . '/api/v1/get-single-sensor', $jsonData);
        
                            // Cek apakah response berhasil
                            if ($response->successful()) {
                                $responseJson = $response->json(); // Mengambil data dari response
                                $response_data_sensor = $responseJson['data'];
                                
                                $allDevice[$index2][] = [
                                    "device_id" => $device['id'],
                                    'device_name' => $device['device_name'],
                                    'group_name' => $device['group_name'],
                                    'is_line' => $device['is_line'],
                                    "sensor_name" => $device['sensor_name'],
                                    "point_code" => $device['point_code'],
                                    "address" => $device['address'],
                                    "value" => $response_data_sensor['value'],
                                    'unit' => $response_data_sensor['unit'],
                                    'updated_at' => $response_data_sensor['updateDate'],
                                ];
                            }else{
                                return back()->withErrors('Gagal mengambil data dari API: ' . $response->body());
                            }
                        }else{
                            $allDevice[$index2][] = [
                                "device_id" => $device['id'],
                                'device_name' => $device['device_name'],
                                'group_name' => $device['group_name'],
                                'is_line' => $device['is_line'],
                                "sensor_name" => $device['sensor_name'],
                                "point_code" => $device['point_code'],
                                "address" => $device['address'],
                                "value" => 0,
                                'unit' => 'm',
                                'updated_at' => null,
                            ];
                        }
                    }
                }
            }

            $data['all_devices'] = $allDevice;

            return view('dashboard.index', $data);
        } else {
            return back()->withErrors('Gagal mengambil data dari API: ' . $response->body());
        }
    }
}
