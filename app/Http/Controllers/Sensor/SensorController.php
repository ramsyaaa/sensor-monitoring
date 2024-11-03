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

}
