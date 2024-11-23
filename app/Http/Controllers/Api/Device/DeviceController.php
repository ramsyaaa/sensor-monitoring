<?php

namespace App\Http\Controllers\Api\Device;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class DeviceController extends Controller
{
    public function getDeviceList(Request $request){
        $from_api = $request->from_api ? $request->from_api : 0;
        $search = $request->search ? $request->search : null;
        
        $token = session('access_token');
        if (!$token) {
            if($from_api == 1){
                return response()->json([
                    'message' => 'Token not found',
                ], 403);
            }
            return null;
        }
        $response = Http::timeout(20)->withoutVerifying()
            ->withHeaders([
                'Authorization' => 'Bearer ' . $token,
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ])
            ->get(env('URL_API') . '/api/v1/geomapping/device-list', [
                'keyword' => $search,
            ]);

        $data['allDevices'] = [];
        // Cek apakah response berhasil
        if ($response->successful()) {
            $data_response = $response->json(); // Mengambil data dari response
            $device = $data_response['data'];
            $data['allDevices'] = $device;
            if($from_api == 1){
                return response()->json([
                    'message' => 'Data found',
                    'data' => $device,
                ], 200);
            }
            return $data;
        } else {
            if($from_api == 1){
                return response()->json([
                    'message' => 'API error',
                ], 500);
            }
            return null;
        }
    }
}
