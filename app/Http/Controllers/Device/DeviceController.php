<?php

namespace App\Http\Controllers\Device;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class DeviceController extends Controller
{
    public function index(Request $request)
    {
        $data['group_id'] = $request->group_id ? $request->group_id : null;
        $token = session('access_token'); // Ambil token dari session

        if (!$token) {
            return redirect()->route('login')->withErrors('Token tidak ditemukan atau sudah kedaluwarsa.');
        }

        // Menyiapkan data JSON untuk dikirim
        $jsonData = [
            'group_id' => $data['group_id'],
            'city_id' => null,
            'district_id' => null,
            'subdistrict_id' => null,
            'keyword' => null,
        ];

        // Mengirim permintaan POST ke API dengan JSON di body
        $response = Http::timeout(20)->withoutVerifying()
            ->withHeaders([
                'Authorization' => 'Bearer ' . $token,
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ])
            ->get(env('URL_API') . '/api/v1/geomapping/device-list', $jsonData);

        // Cek apakah response berhasil
        if ($response->successful()) {
            $responseJson = $response->json(); // Mengambil data dari response
            $response_data = $responseJson['data'];
            $data['devices'] = $response_data;

            $response = Http::timeout(20)->withoutVerifying()
            ->withHeaders([
                'Authorization' => 'Bearer ' . $token,
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ])
            ->get(env('URL_API') . '/api/v1/geomapping/group-list');

        $data['groups'] = [];
        if ($response->successful()) {
            $response = $response->json();
            $data['groups'] = $response['data'];
        }

            return view('device.index', $data);
        } else {
            return back()->withErrors('Gagal mengambil data dari API: ' . $response->body());
        }
    }

    public function show($id){
        $token = session('access_token'); // Ambil token dari session

        if (!$token) {
            return redirect()->route('login')->withErrors('Token tidak ditemukan atau sudah kedaluwarsa.');
        }

        // Menyiapkan data JSON untuk dikirim
        $jsonData = [
            'userId' => 99837,
            "deviceId" => intval($id),
            'currPage' => 1,
            'pageSize' => 10,
        ];

        // Mengirim permintaan POST ke API dengan JSON di body
        $response = Http::timeout(20)->withoutVerifying()
            ->withHeaders([
                'Authorization' => 'Bearer ' . $token,
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ])
            ->post(env('URL_API') . '/api/v1/get-single-device', $jsonData);

        // Cek apakah response berhasil
        if ($response->successful()) {
            $data = $response->json(); // Mengambil data dari response
            $data = $data['data'];
            $sensors = $data['device']['sensorsList'];

            $response = Http::timeout(20)->withoutVerifying()
                ->withHeaders([
                    'Authorization' => 'Bearer ' . $token,
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                ])
                ->post(env('URL_API') . '/api/v1/geomapping/device-detail', ["deviceId" => intval($id)]);

            // Cek apakah response berhasil
            if ($response->successful()) {
                $response_data_api = $response->json(); // Mengambil response_data_api dari response
                $response_data_api = $response_data_api['data'][0];
                $data = array_merge($data, $response_data_api);
            }
            if(isset($_GET['from_map'])){
                return view('maps.show', compact('data', 'sensors'));
            }
            return view('device.show', compact('data', 'sensors'));
        } else {
            return back()->withErrors('Gagal mengambil data dari API: ' . $response->body());
        }
        return view('device.show');
    }

    public function create(){
        return view('device.create');
    }

    public function edit($id){
        $token = session('access_token'); // Ambil token dari session

        if (!$token) {
            return redirect()->route('login')->withErrors('Token tidak ditemukan atau sudah kedaluwarsa.');
        }

        $role = session('role');

        if($role != 'admin'){
            return abort(403);
        }

        // Menyiapkan data JSON untuk dikirim
        $jsonData = [
            "deviceID" => intval($id),
        ];

        // Mengirim permintaan POST ke API dengan JSON di body
        $response = Http::timeout(20)->withoutVerifying()
            ->withHeaders([
                'Authorization' => 'Bearer ' . $token,
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ])
            ->post(env('URL_API') . '/api/v1/geomapping/device-detail', $jsonData);

        // Cek apakah response berhasil
        if ($response->successful()) {
            $data = $response->json(); // Mengambil data dari response
            $data = $data['data'][0];
            $data['device'] = $data;
    
            // Mengirim permintaan POST ke API dengan JSON di body
            $response = Http::timeout(20)->withoutVerifying()
                ->withHeaders([
                    'Authorization' => 'Bearer ' . $token,
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                ])
                ->get(env('URL_API') . '/api/v1/geomapping/city-list');
    
            // Cek apakah response berhasil
            $data['cities'] = [];
            if ($response->successful()) {
                $responseData = $response->json(); // Mengambil data dari response
                $data['cities'] = $responseData['data'];
            }

            return view('device.edit', $data);
        } else {
            return back()->withErrors('Gagal mengambil data dari API: ' . $response->body());
        }        
    }

    public function update(Request $request, $id){
        $role = session('role');

        if($role != 'admin'){
            return abort(403);
        }
        
        $request->validate([
            "city_id" => 'required',
            "district_id" => 'required',
            "subdistrict_id" => 'required',
            "point_code" => 'required',
            "address" => 'required',
            "lat" => 'required',
            "lng" => 'required',
            "electrical_panel" => 'required',
            "surrounding_waters" => 'required',
            "location_information" => 'required',
            "note" => 'required',
        ], [
            "city_id.required" => 'City field is required',
            "district_id.required" => 'District field is required',
            "subdistrict_id.required" => 'Subdistrict field is required',
            "point_code.required" => 'Point code field is required.',
            "address.required" => 'Address field is required.',
            "lat.required" => 'Latitude field is required.',
            "lng.required" => 'Longitude field is required.',
            "electrical_panel.required" => 'Electrical panel field is required.',
            "surrounding_waters.required" => 'Surrounding waters field is required.',
            "location_information.required" => 'Location information field is required.',
            "note.required" => 'Note field is required.',
        ]);

        $token = session('access_token'); // Ambil token dari session

        if (!$token) {
            return redirect()->route('login')->withErrors('Token tidak ditemukan atau sudah kedaluwarsa.');
        }

        // Menyiapkan data JSON untuk dikirim
        $jsonData = [
            "deviceID" => intval($id),
        ];

        // Mengirim permintaan POST ke API dengan JSON di body
        $response = Http::timeout(20)->withoutVerifying()
            ->withHeaders([
                'Authorization' => 'Bearer ' . $token,
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ])
            ->post(env('URL_API') . '/api/v1/geomapping/device-detail', $jsonData);

        // Cek apakah response berhasil
        if ($response->successful()) {
            $data = $response->json(); // Mengambil data dari response
            $data = $data['data'][0];
            $data['device'] = $data;
            

            $jsonData = [
                "device_id" => intval($id),
                "city_id" => intval($request->city_id),
                "district_id" => intval($request->district_id),
                "subdistrict_id" => intval($request->subdistrict_id),
                "point_code" => $request->point_code,
                "address" => $request->address,
                "lat" => $request->lat,
                "lng" => $request->lng,
                "electrical_panel" => $request->electrical_panel,
                "surrounding_waters" => $request->surrounding_waters,
                "location_information" => $request->location_information,
                "note" => $request->note,
            ];
    
            // Mengirim permintaan POST ke API dengan JSON di body
            $response = Http::timeout(20)->withoutVerifying()
                ->withHeaders([
                    'Authorization' => 'Bearer ' . $token,
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                ])
                ->put(env('URL_API') . '/api/v1/geomapping/update-device', $jsonData);
            if ($response->successful()) {
                return redirect(route('device.index'))->with('success', 'Device updated successfully!');
            }else{
                dd($response->body());
                return back()->withErrors('API Error: ' . $response->body());
            }
        } else {
            return back()->withErrors('Gagal mengambil data dari API: ' . $response->body());
        }        
    }
}
