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
                        if (isset($device['sensor_id']) && $device['sensor_id'] != null && $device['is_line'] == 1) {
                            $dataOverviewDevice[$index2]["active"] += 1;
                            $dataOverviewDevice[$index2]["total"] += 1;
                            $jsonData = [
                                'userId' => 99837,
                                'sensorId' => intval($device['sensor_id']),
                            ];

                            $sensorResponse = Http::timeout(20)->withoutVerifying()
                                ->withHeaders([
                                    'Authorization' => 'Bearer ' . $token,
                                    'Accept' => 'application/json',
                                    'Content-Type' => 'application/json',
                                ])
                                ->post(env('URL_API') . '/api/v1/get-single-sensor', $jsonData);

                            if ($sensorResponse->successful()) {
                                $responseJson = $sensorResponse->json();
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
                            } else {
                                return response()->json([
                                    'success' => false,
                                    'message' => 'Gagal mengambil data dari API: ' . $sensorResponse->body(),
                                ], 500);
                            }
                        } else {
                            $dataOverviewDevice[$index2]["total"] += 1;
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
