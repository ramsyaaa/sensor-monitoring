<?php

namespace App\Http\Controllers\Device;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DeviceController extends Controller
{
    public function index(){
        return view('device.index');
    }

    public function show($id){
        return view('device.show');
    }

    public function create(){
        return view('device.create');
    }

    public function edit($id){
        return view('device.edit');
    }
}
