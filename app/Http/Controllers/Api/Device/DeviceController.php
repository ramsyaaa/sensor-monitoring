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

            foreach ($data['allDevices'] as $index => $single_device) {
                $device[$index]['sensors'] = [
                    "flow_meter" => 0,
                    "unit_flow_meter" => "m/s",
                    "water_level" => 0,
                    "unit_water_level" => 'm',
                    'instantaneous_flow' => 0,
                    "unit_instantaneous_flow" => "m³/s",
                ];
                // if($single_device['is_line'] == 1){
                //     $response = Http::timeout(20)->withoutVerifying()
                //         ->withHeaders([
                //             'Authorization' => 'Bearer ' . $token,
                //             'Accept' => 'application/json',
                //             'Content-Type' => 'application/json',
                //         ])
                //         ->post(env('URL_API') . '/api/v1/get-single-device', [
                //             "userId" => 99837,
                //             "deviceId" => intval($single_device['id']),
                //             "currPage" => 1,
                //             "pageSize" => 10
                //         ]);

                //     if ($response->successful()) {
                //         $data_response = $response->json();
                //         $sensors = $data_response['data']['device']['sensorsList'];

                //         $device[$index]['sensors'] = [
                //             "flow_meter" => 0,
                //             "unit_flow_meter" => "m/s",
                //             "water_level" => 0,
                //             "unit_water_level" => 'm',
                //             'instantaneous_flow' => 0,
                //             "unit_instantaneous_flow" => "m³/s",
                //         ];

                //         foreach ($sensors as $key => $sensor) {
                //             if($sensor['sensorName'] == "Flow velocity"){
                //                 $device[$index]['sensors']["flow_meter"] = $sensor["value"];
                //             }
                //             if($sensor['sensorName'] == "Water level"){
                //                 $device[$index]['sensors']["water_level"] = $sensor["value"];
                //             }
                //             if($sensor['sensorName'] == "Instantaneous flow"){
                //                 $device[$index]['sensors']["instantaneous_flow"] = $sensor["value"];
                //             }
                //         }
                //     }else{
                //         $device[$index]['sensors'] = [
                //             "flow_meter" => 0,
                //             "unit_flow_meter" => "m/s",
                //             "water_level" => 0,
                //             "unit_water_level" => 'm',
                //             'instantaneous_flow' => 0,
                //             "unit_instantaneous_flow" => "m³/s",
                //         ];
                //     }
                // }else{
                //     $device[$index]['sensors'] = [
                //         "flow_meter" => 0,
                //         "unit_flow_meter" => "m/s",
                //         "water_level" => 0,
                //         "unit_water_level" => 'm',
                //         'instantaneous_flow' => 0,
                //         "unit_instantaneous_flow" => "m³/s",
                //     ];
                // }
            }

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
