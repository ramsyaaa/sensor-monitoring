<?php

namespace App\Http\Controllers\Territory;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class TerritoryController extends Controller
{
    public function getDistrict($city_id)
    {
        $token = session('access_token'); // Ambil token dari session

        if (!$token) {
            return response()->json([
                'error' => 'Token tidak ditemukan atau sudah kedaluwarsa.',
            ], 401);
        }

        $jsonData = [
            "city_id" => intval($city_id),
        ];

        // Mengirim permintaan GET ke API dengan JSON di body
        $response = Http::timeout(20)->withoutVerifying()
            ->withHeaders([
                'Authorization' => 'Bearer ' . $token,
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ])
            ->get(env('URL_API') . '/api/v1/geomapping/district-list', $jsonData);

        if ($response->successful()) {
            $data = $response->json(); // Mengambil data dari response
            $districts = $data['data'] ?? []; // Ambil array 'data' dari response API

            return response()->json([
                'success' => true,
                'districts' => $districts,
            ]);
        } else {
            return response()->json([
                'error' => 'API Error',
                'message' => $response->body(),
            ], $response->status());
        }
    }

    public function getSubdistrict($district_id)
    {
        $token = session('access_token'); // Ambil token dari session

        if (!$token) {
            return response()->json([
                'error' => 'Token tidak ditemukan atau sudah kedaluwarsa.',
            ], 401);
        }

        $jsonData = [
            "district_id" => intval($district_id),
        ];

        // Mengirim permintaan GET ke API dengan JSON di body
        $response = Http::timeout(20)->withoutVerifying()
            ->withHeaders([
                'Authorization' => 'Bearer ' . $token,
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ])
            ->get(env('URL_API') . '/api/v1/geomapping/subdistrict-list', $jsonData);

        if ($response->successful()) {
            $data = $response->json(); // Mengambil data dari response
            $subdistricts = $data['data'] ?? []; // Ambil array 'data' dari response API

            return response()->json([
                'success' => true,
                'subdistricts' => $subdistricts,
            ]);
        } else {
            return response()->json([
                'error' => 'API Error',
                'message' => $response->body(),
            ], $response->status());
        }
    }

}
