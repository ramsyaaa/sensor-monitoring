<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ApiDashboardController extends Controller
{
    public function getDashboard()
    {
        $token = session('access_token'); // Ambil token dari session

        if (!$token) {
            return response()->json([
                'success' => false,
                'message' => 'Token tidak ditemukan atau sudah kedaluwarsa.',
            ], 401);
        }

        $response = Http::timeout(20)->withoutVerifying()
            ->withHeaders([
                'Authorization' => 'Bearer ' . $token,
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ])
            ->get(env('URL_API') . '/api/v1/geomapping/dashboard');

        if ($response->successful()) {
            $responseJson = $response->json();
            $response_data = $responseJson['data'];

            $allDevice = [];
            $dataOverviewDevice = [];
            foreach ($response_data as $index => $groups) {
                foreach ($groups as $index2 => $group) {
                    $allDevice[$index2] = [];
                    $dataOverviewDevice[$index2] = [
                        "active" => 0,
                        "total" => 0,
                    ];

                    foreach ($group as $index3 => $device) {
                        $allDevice[$index2][] = $device;
                        if ($device['is_line'] == 1) {
                            $dataOverviewDevice[$index2]["active"] += 1;
                            $dataOverviewDevice[$index2]["total"] += 1;                            
                        } else {
                            $dataOverviewDevice[$index2]["total"] += 1;
                        }
                    }
                }
            }

            $nationalActive = 0;
            $nationalTotal = 0;

            foreach ($dataOverviewDevice as $city => $values) {
                $nationalActive += $values['active'];
                $nationalTotal += $values['total'];
            }

            $dataOverviewDevice['DKI Jakarta'] = [
                "active" => $nationalActive,
                "total" => $nationalTotal,
            ];

            return response()->json([
                'success' => true,
                'data' => [
                    'all_devices' => $allDevice,
                    'data_overview_device' => $dataOverviewDevice,
                ],
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data dari API: ' . $response->body(),
            ], 500);
        }
    }

}
