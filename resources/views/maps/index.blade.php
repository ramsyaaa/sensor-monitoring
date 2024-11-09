@extends('layouts.app')

@section('content')
<style>
    #detail-panel {
        transition: transform 0.3s ease;
    }
    .invisible-panel {
        transform: translateX(100%);
    }
</style>
    <div x-data="{ sidebar: true, popupNavbar: false }" class="relative flex">
        <div class="absolute w-full h-[250px] bg-[#14176c] -z-10">

        </div>
        @include('components.sidebar')
        <div class="min-h-screen" :class="sidebar ? 'w-10/12' : 'w-full'">
            @include('components.navbar')
            <div class="container mx-auto p-4 max-h-screen overflow-auto">
                @include('components.breadcrumb', [
                    'lists' => [
                        [
                            'title' => 'Maps',
                            'route' => '#',
                            'is_active' => true,
                        ],
                    ],
                ])
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-2xl font-semibold text-gray-800 mb-4 text-white">Maps</h2>
                </div>

                <div class="overflow-x-auto bg-white px-6 py-10 rounded-lg shadow-lg">
                    <div id="map" style="width: 100%; height: 500px;"></div>
                </div>
                <div id="detail-panel" style="z-index: 1000" class="fixed top-0 right-0 w-full md:w-2/3 h-screen bg-white shadow-lg p-4 invisible-panel overflow-y-auto">
                    <button onclick="closePanel()" class="absolute top-2 right-4 text-gray-600 text-[24px]">&times;</button>
                    <div id="detail-content"></div>
                </div>
            </div>
        </div>
    </div>

    @include('components.chart')

    <script>
        // Fungsi untuk menampilkan detail
        function showDetail(id) {
            // Tampilkan panel
            document.getElementById('detail-panel').classList.remove('invisible-panel');

            // Kirim request untuk mendapatkan data dari API dan tambahkan `from_map=true` dalam parameter
            fetch(`/devices/${id}?from_map=true`, {
                method: 'GET',
            })
            .then(response => response.text())  // Mengambil sebagai teks HTML
            .then(data => {
                // Isi konten detail dengan data respons
                document.getElementById('detail-content').innerHTML = data;
            })
            .catch(error => console.error('Error:', error));
        }

        // Fungsi untuk menutup panel
        function closePanel() {
            document.getElementById('detail-panel').classList.add('invisible-panel');
            document.getElementById('detail-content').innerHTML = ''; // Kosongkan konten
        }

        var devices = {!! json_encode($data) !!};

        // Tentukan lokasi default (Bandung) jika array devices kosong
        var defaultLat = -6.9175;
        var defaultLng = 107.6191;
        var initialLat = devices.length > 0 ? devices[0].lat : defaultLat;
        var initialLng = devices.length > 0 ? devices[0].lng : defaultLng;

        // Inisialisasi peta dan atur tampilan awal pada posisi perangkat pertama atau default dengan zoom lebih dekat
        var map = L.map('map').setView([initialLat, initialLng], 18); // Zoom lebih dekat pada 15

        // Tambahkan layer tile dari OpenStreetMap
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);

        // Menambahkan marker untuk setiap perangkat
        devices.forEach(function(device) {
            L.marker([device.lat, device.lng]).addTo(map)
                .bindPopup(device.device_name)
                .on('click', function() {
                    showDetail(device.id);
                });
        });

        // Setel ulang tampilan peta setelah semua marker ditambahkan, 
        // gunakan posisi perangkat pertama atau tetap di posisi default jika tidak ada perangkat
        if (devices.length > 0) {
            map.setView([devices[0].lat, devices[0].lng], 15); // Zoom lebih dekat pada 15
        } else {
            map.setView([defaultLat, defaultLng], 15); // Zoom lebih dekat pada 15
        }

    </script>
    
    
@endsection
