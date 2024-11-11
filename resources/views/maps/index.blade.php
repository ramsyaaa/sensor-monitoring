@extends('layouts.app')

@section('content')
<style>
    #detail-panel {
        transition: transform 0.3s ease;
    }
    .invisible-panel {
        transform: translateX(100%);
    }
    #map {
        height: 100%; /* atau ukuran spesifik lainnya, seperti 500px */
        width: 100%;
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
                    <form method="GET" class="flex gap-4 items-center mb-4">
                        <select name="device" id="device" class="w-full px-4 py-2 rounded-lg border">
                            <option value="">Pilih</option>
                            @foreach ($device as $item)
                            <option @if($item['id'] == request()->device) selected @endif value="{{ $item['id'] }}">{{ $item['device_name'] }}</option>    
                            @endforeach
                        </select>
                        <button type="submit" class="px-4 py-2 text-white bg-blue-500 rounded-lg shadow-lg">Search</button>
                    </form>
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
        window.onload = function() {
    
            var devices = {!! json_encode($allDevices) !!};

            // Tentukan lokasi default (Bandung) jika array devices kosong
            var defaultLat = -6.9175;
            var defaultLng = 107.6191;
            var initialLat = devices.length > 0 ? devices[0].lat : defaultLat;
            var initialLng = devices.length > 0 ? devices[0].lng : defaultLng;

            // Inisialisasi peta dan atur tampilan awal pada posisi perangkat pertama atau default dengan zoom lebih dekat
            var map = L.map('map').setView([initialLat, initialLng], 15); // Zoom pada 15

            // Tambahkan layer tile dari OpenStreetMap
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '© OpenStreetMap contributors'
            }).addTo(map);

            // Variabel untuk menyimpan semua posisi marker
            var bounds = [];

            // Fungsi untuk mendapatkan URL ikon berdasarkan nama sensor
            function getSensorIcon(sensorName) {
                switch (sensorName) {
                    case 'Flow velocity':
                        return '{{ asset('asset/img/Icon/flow-velocity.png') }}';
                    case 'Water level':
                        return '{{ asset('asset/img/Icon/water-level.png') }}';
                    case 'Instantaneous flow':
                        return '{{ asset('asset/img/Icon/instantaneous-flow.png') }}';
                    case 'Height':
                        return '{{ asset('asset/img/Icon/height.png') }}';
                    case 'Temperature':
                        return '{{ asset('asset/img/Icon/temperature.png') }}';
                    case 'Humidity':
                        return '{{ asset('asset/img/Icon/humidity.png') }}';
                    case 'Noise':
                        return '{{ asset('asset/img/Icon/noice.png') }}';
                    case 'Wind Speed':
                        return '{{ asset('asset/img/Icon/wind.png') }}';
                    case 'Wind Direction':
                        return '{{ asset('asset/img/Icon/wind-direction.png') }}';
                    case 'PM2.5':
                        return '{{ asset('asset/img/Icon/pm2.png') }}';
                    case 'PM10':
                        return '{{ asset('asset/img/Icon/pm10.png') }}';
                    case 'Atmosphere':
                        return '{{ asset('asset/img/Icon/atmosphere.png') }}';
                    case 'Optical rainfall':
                        return '{{ asset('asset/img/Icon/rainfall.png') }}';
                    default:
                        return 'http://webplus-cn-shenzhen-s-5decf7913c3f2876a5adc591.oss-cn-shenzhen.aliyuncs.com/images/temperature.png';
                }
            }

            // Menambahkan marker untuk setiap perangkat
            devices.forEach(function(device) {
                if (device.lat && device.lng) {  // Pastikan lat dan lng ada
                    var position = [parseFloat(device.lat), parseFloat(device.lng)];
                    
                    // // Jika sumbernya dari data_list, tambahkan offset agar tidak menumpuk
                    // if (device.source === 'data_list') {
                    //     position[0] += 0.00005; // offset latitude
                    //     position[1] += 0.00005; // offset longitude
                    // }

                    // Tentukan ikon berdasarkan sumber data
                    var iconUrl = device.source === 'data' 
                        ? 'https://webplus-cn-shenzhen-s-5decf7913c3f2876a5adc591.oss-cn-shenzhen.aliyuncs.com/fileUpload/productImg/20210813/20210813134239_48.jpg'
                        : getSensorIcon(device.sensor_name);

                    // Konfigurasi ikon marker
                    var icon = L.icon({
                        iconUrl: iconUrl,
                        iconSize: [32, 32], // Ukuran ikon bisa disesuaikan
                        iconAnchor: [16, 32] // Atur posisi anchor agar ikon terlihat bagus
                    });

                    // Tambahkan posisi ke bounds
                    bounds.push(position);
                    
                    // Tambahkan marker ke peta
                    L.marker(position, { icon: icon }).addTo(map)
                        .bindPopup(device.source === 'data_list' ? device.sensor_name : device.device_name)
                        .on('click', function() {
                            if (device.source === 'data') {
                                showDetail(device.id);
                            } else if (device.source === 'data_list') {
                                getRealtime(device.id);
                            }
                        });
                } 
            });

            // Sesuaikan peta agar semua marker terlihat
            if (bounds.length > 0) {
                map.fitBounds(bounds);
            } else {
                map.setView([defaultLat, defaultLng], 15); // Zoom pada 15 jika tidak ada marker
            }

            map.invalidateSize();
        };
    </script>
    
    
@endsection
