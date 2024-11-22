<?php

namespace App\Http\Controllers\Map;

use App\Http\Controllers\Api\Device\DeviceController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MapController extends Controller
{
    public function index(Request $request){
        

        $controller = new DeviceController();
        $data = $controller->getDeviceList($request);

        if($data == null){
            return back()->withErrors('Gagal mengambil data dari API');
        }

        // Mengirim permintaan POST ke API dengan JSON di body
        return view('maps.index', $data);
    }
}
