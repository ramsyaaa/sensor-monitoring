<?php

namespace App\Http\Controllers\Map;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class MapController extends Controller
{
    public function index(Request $request){
        $token = session('access_token'); // Ambil token dari session
        $city_id = isset($request->city_id) ?  $request->city_id : null;
        $district_id = isset($request->district_id) ?  $request->district_id : null;
        $subdistrict_id = isset($request->subdistrict_id) ?  $request->subdistrict_id : null;
        $group_id = isset($request->group_id) ?  $request->group_id : null;

        $data['allDevices'] = [];

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
            ->get(env('URL_API') . '/api/v1/geomapping/device-list', [
                'group_id' => $group_id,
                'city_id' => $city_id,
                'district_id' => $district_id,
                'subdistrict_id' => $subdistrict_id
            ]);

        // Cek apakah response berhasil
        if ($response->successful()) {
            $data_response = $response->json(); // Mengambil data dari response
            $device = $data_response['data'];
            $data['allDevices'] = $device;
            $data['device'] = $device;

            $response = Http::timeout(20)->withoutVerifying()
            ->withHeaders([
                'Authorization' => 'Bearer ' . $token,
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ])
            ->get(env('URL_API') . '/api/v1/geomapping/group-list');

            $data['groups'] = [];
            if ($response->successful()) {
                $data_response = $response->json();
                $data['groups'] = $data_response['data'];
            }else{
                return back()->withErrors('Gagal mengambil data dari API: ' . $response->body());
            }

            $response = Http::timeout(20)->withoutVerifying()
            ->withHeaders([
                'Authorization' => 'Bearer ' . $token,
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ])
            ->get(env('URL_API') . '/api/v1/geomapping/city-list');

            $data['cities'] = [];
            if ($response->successful()) {
                $data_response = $response->json();
                $data['cities'] = $data_response['data'];
            }else{
                return back()->withErrors('Gagal mengambil data dari API: ' . $response->body());
            }

            $data['districts'] = [];
            if(isset($city_id)){
                if($city_id != null){
                    $response = Http::timeout(20)->withoutVerifying()
                    ->withHeaders([
                        'Authorization' => 'Bearer ' . $token,
                        'Accept' => 'application/json',
                        'Content-Type' => 'application/json',
                    ])
                    ->get(env('URL_API') . '/api/v1/geomapping/district-list', [
                        'city_id' => $city_id
                    ]);

                    if ($response->successful()) {
                        $data_response = $response->json();
                        $data['districts'] = $data_response['data'];
                    }else{
                        return back()->withErrors('Gagal mengambil data dari API: ' . $response->body());
                    }
                }else{
                    $district_id = null;
                    $subdistrict_id = null;
                }
            }

            $data['subdistricts'] = [];
            if(isset($district_id)){
                if($district_id != null){
                    $response = Http::timeout(20)->withoutVerifying()
                    ->withHeaders([
                        'Authorization' => 'Bearer ' . $token,
                        'Accept' => 'application/json',
                        'Content-Type' => 'application/json',
                    ])
                    ->get(env('URL_API') . '/api/v1/geomapping/subdistrict-list', [
                        'district_id' => $district_id
                    ]);

                    if ($response->successful()) {
                        $data_response = $response->json();
                        $data['subdistricts'] = $data_response['data'];
                    }else{
                        return back()->withErrors('Gagal mengambil data dari API: ' . $response->body());
                    }
                }else{
                    $subdistrict_id = null;
                }
            }

            $data['city_id'] = $city_id;
            $data['district_id'] = $district_id;
            $data['subdistrict_id'] = $subdistrict_id;
            $data['group_id'] = $group_id;

            return view('maps.index', $data);
        } else {
            return back()->withErrors('Gagal mengambil data dari API: ' . $response->body());
        }
    }
}
