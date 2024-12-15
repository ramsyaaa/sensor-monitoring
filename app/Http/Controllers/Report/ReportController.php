<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ReportController extends Controller
{
    public function index(){
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
            $data['device'] = $response_data;
            return view('reports.index', $data);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data dari API: ' . $response->body(),
            ], 500);
        }
    }

    public function reportList(){
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
            ->get(env('URL_API') . '/api/v1/report/list');

        if ($response->successful()) {
            $responseJson = $response->json();
            $response_data = $responseJson['data'];
            return response()->json([
                'success' => true,
                'data' => $response_data,
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data dari API: ' . $response->body(),
            ], 500);
        }
    }
    
    public function download($id)
    {
        $token = session('access_token'); // Ambil token dari session

        if (!$token) {
            return response()->json([
                'success' => false,
                'message' => 'Token tidak ditemukan atau sudah kedaluwarsa.',
            ], 401);
        }
        
        // Meminta file dari API eksternal
        $response = Http::timeout(20)->withoutVerifying()
            ->withHeaders([
                'Authorization' => 'Bearer ' . $token,
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ])
            ->post(env('URL_API') . '/api/v1/report/download/' . intval($id));

        if ($response->successful()) {
            // Tentukan tipe file jika perlu
            $fileName = 'report_' . $id . '.xlsx'; // Ganti dengan nama file yang sesuai
            $fileContent = $response->body(); // Ambil konten file dari respons API

            // Mengembalikan file sebagai unduhan
            return response()->streamDownload(function () use ($fileContent) {
                echo $fileContent;
            }, $fileName, [
                'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', // Tipe file XLSX
                'Content-Disposition' => 'attachment; filename="' . $fileName . '"', // Memaksa unduhan
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data dari API: ' . $response->body(),
            ], 500);
        }
    }


    public function create(Request $request, $id){
        $data['device_name'] = $request->device_name ?? "-";
        $data['point_code'] = $request->point_code ?? '-';
        $data['id'] = $id;
        return view('reports.show', $data);
    }

    public function store(Request $request, $id){
        {
            // Validasi input
            $validated = $request->validate([
                'start_date' => 'required|date',
                'end_date' => 'required|date|after_or_equal:start_date',
            ], [
                'start_date.required' => 'Start date is required.',
                'end_date.required' => 'End date is required.',
                'end_date.after_or_equal' => 'End date must be equal to or after start date.',
            ]);

            $token = session('access_token'); // Ambil token dari session

            if (!$token) {
                return response()->json([
                    'success' => false,
                    'message' => 'Token tidak ditemukan atau sudah kedaluwarsa.',
                ], 401);
            }

            $jsonData = [
                "device_id" => intval($id),
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
            ];

            $response = Http::timeout(20)->withoutVerifying()
                ->withHeaders([
                    'Authorization' => 'Bearer ' . $token,
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                ])
                ->post(env('URL_API') . '/api/v1/report/generate', $jsonData);

            if ($response->successful()) {
                return response()->json([
                    'message' => 'Report created successfully!',
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Fail to fetch the API: ' . $response->body(),
                ], 500);
            }
        }
    }
}
