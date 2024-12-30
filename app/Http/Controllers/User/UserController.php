<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class UserController extends Controller
{
    public function __construct()
    {
        $role = session('role');

        if($role != 'admin'){
            return abort(403);
        }
    }

    public function index(Request $request)
    {
        $token = session('access_token'); // Ambil token dari session

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
            ->get(env('URL_API') . '/api/v1/user/list');

        // Cek apakah response berhasil
        if ($response->successful()) {
            $responseJson = $response->json(); // Mengambil data dari response
            $response_data = $responseJson['data'];
            $data['users'] = $response_data;

            return view('user.index', $data);
        } else {
            return back()->withErrors('Gagal mengambil data dari API: ' . $response->body());
        }
    }

    public function create(){
        return view('user.create');
    }

    public function edit(Request $request, $id){
        $data['id'] = $id;
        $data['username']  = $request->username;
        return view('user.edit', $data);
    }

    public function store(Request $request){
        $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        $token = session('access_token'); // Ambil token dari session

        if (!$token) {
            return redirect()->route('login')->withErrors('Token tidak ditemukan atau sudah kedaluwarsa.');
        }

        $jsonData = [
            'username' => $request->username,
            'password' => $request->password,
        ];

        // Mengirim permintaan POST ke API dengan JSON di body
        $response = Http::timeout(20)->withoutVerifying()
            ->withHeaders([
                'Authorization' => 'Bearer ' . $token,
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ])
            ->post(env('URL_API') . '/api/v1/user/create', $jsonData);

        // Cek apakah response berhasil
        if ($response->successful()) {
            return redirect(route('user.index'))->with('success', 'User created successfully!');
        } else {
            $data = $response->json();
            $error_message = $data['meta']['message'];
            return back()
                ->withInput()
                ->withErrors(['username' => $error_message]);
        }
    }

    public function update(Request $request, $id){
        $token = session('access_token'); // Ambil token dari session

        if (!$token) {
            return redirect()->route('login')->withErrors('Token tidak ditemukan atau sudah kedaluwarsa.');
        }

        $jsonData = [
            'password' => $request->password,
        ];

        // Mengirim permintaan POST ke API dengan JSON di body
        $response = Http::timeout(20)->withoutVerifying()
            ->withHeaders([
                'Authorization' => 'Bearer ' . $token,
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ])
            ->put(env('URL_API') . '/api/v1/user/edit/' . $id, $jsonData);

            dd($response);

        // Cek apakah response berhasil
        if ($response->successful()) {
            return redirect(route('user.index'))->with('success', 'User password updated successfully!');
        } else {
            $data = $response->json();
            $error_message = $data['meta']['message'];
            return redirect(route('user.index'))->with('error', $error_message);
        }
    }

    public function destroy(Request $request, $id)
    {
        $token = session('access_token'); // Ambil token dari session

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
            ->delete(env('URL_API') . '/api/v1/user/delete/' . $id);

        // Cek apakah response berhasil
        if ($response->successful()) {
            return redirect(route('user.index'))->with('success', 'User deleted successfully!');
        } else {
            return back()->withErrors('Gagal mengambil data dari API: ' . $response->body());
        }
    }
}
