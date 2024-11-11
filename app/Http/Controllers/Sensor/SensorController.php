<?php

namespace App\Http\Controllers\Sensor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;

class SensorController extends Controller
{
    public function realtime($id, Request $request)
    {
        $token = session('access_token'); // Ambil token dari session

        if (!$token) {
            return response()->json(['error' => 'Token tidak ditemukan atau sudah kedaluwarsa.'], 401);
        }

        // UserId dan pageSize yang tetap
        $userId = 99837;
        $pageSize = 1000;
        $pagingState = "";

        // Ambil waktu saat ini (WIB) dan 10 menit sebelumnya
        $defaultEndDate = Carbon::now('Asia/Jakarta')->format('Y-m-d H:i:s');
        $defaultStartDate = Carbon::now('Asia/Jakarta')->subMinutes(10)->format('Y-m-d H:i:s');

        // Cek apakah startDate dan endDate ada dalam request
        $startDateInput = $request->input('startDate');
        $endDateInput = $request->input('endDate');

        // Konversi jika ada, jika tidak, gunakan default
        $startDate = $startDateInput ? Carbon::createFromFormat('Y-m-d\TH:i', $startDateInput)->format('Y-m-d H:i:s') : $defaultStartDate;
        $endDate = $endDateInput ? Carbon::createFromFormat('Y-m-d\TH:i', $endDateInput)->format('Y-m-d H:i:s') : $defaultEndDate;

        // Menyiapkan data JSON untuk dikirim ke API
        $jsonData = [
            'userId' => $userId,
            'sensorId' => intval($id),
            'startDate' => $startDate,
            'endDate' => $endDate,
            'pagingState' => $pagingState,
            'pageSize' => $pageSize,
        ];

        // Mengirim permintaan POST ke API
        $response = Http::timeout(20)->withoutVerifying()
            ->withHeaders([
                'Authorization' => 'Bearer ' . $token,
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ])
            ->post(env('URL_API') . '/api/v1/get-sensor-history', $jsonData);

        // Memeriksa apakah respons berhasil
        if ($response->successful()) {
            return response()->json($response->json());
        } else {
            return response()->json(['error' => 'Gagal mengambil data dari API'], $response->status());
        }
    }

    public function edit(Request $request, $id){
        $token = session('access_token'); // Ambil token dari session

        if (!$token) {
            return redirect()->route('login')->withErrors('Token tidak ditemukan atau sudah kedaluwarsa.');
        }
        $data['id'] = $id;
        $data['device_id'] = $request->device ?? null;
        $data['sensor'] = [];
        $jsonData = [
            "deviceID" => intval($request->device),
        ];

        // Mengirim permintaan POST ke API dengan JSON di body
        $response = Http::timeout(20)->withoutVerifying()
            ->withHeaders([
                'Authorization' => 'Bearer ' . $token,
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ])
            ->post(env('URL_API') . '/api/v1/geomapping/sensor-list', $jsonData);
            dd($response);

        if ($response->successful()) {
            $sensors = $response->json();
            $sensors = $sensors['data'];

            $sensor = array_filter($sensors, function($item) use ($id) {
                return $item['id'] == $id;
            });
        
            // Mengambil elemen pertama dari hasil filter, atau null jika tidak ditemukan
            $data['sensor'] = !empty($sensor) ? array_values($sensor)[0] : null;
        }else{
            return back()->withErrors('Gagal mengambil data dari API: ' . $response->body());
        }
        return view('sensor.edit', $data);
    }

    public function update(Request $request, $id){
        $request->validate([
            'longitude' => 'required',
            'latitude' => 'required',
        ]);

        $token = session('access_token'); // Ambil token dari session

        if (!$token) {
            return redirect()->route('login')->withErrors('Token tidak ditemukan atau sudah kedaluwarsa.');
        }

        $jsonData = [
            "sensorId" => intval($id),
            "lat" => $request->latitude,
            "lng" => $request->longitude,
        ];

        // Mengirim permintaan POST ke API dengan JSON di body
        $response = Http::timeout(20)->withoutVerifying()
            ->withHeaders([
                'Authorization' => 'Bearer ' . $token,
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ])
            ->put(env('URL_API') . '/api/v1/geomapping/update-sensor', $jsonData);

            if ($response->successful()) {
                return redirect()->back()->with('success', 'Sensor updated successfully!');
            } else {
                return redirect()->back()->with('error', 'Failed to update sensor.');
            }
    }

}
