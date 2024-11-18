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
        <div class="absolute w-full h-[250px] bg-[#083C76] -z-10">

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
                    <form method="GET" class="mb-4">
                        <div class="lg:flex lg:gap-2 w-full">
                            <div class="w-full flex flex-col gap-2">
                                <h3 class="font-bold">Group</h3>
                                <select name="group_id" id="group_id" class="w-full px-4 py-2 rounded-lg border mb-4" onchange="this.form.submit()">
                                    <option value="">Select Group</option>
                                    @foreach ($groups as $item)
                                    <option @if($item['group_id'] == $group_id) selected @endif value="{{ $item['group_id'] }}">{{ $item['group_name'] }}</option>    
                                    @endforeach
                                </select>
                            </div>
                            <div class="w-full flex flex-col gap-2">
                                <h3 class="font-bold">City</h3>
                                <select name="city_id" id="city_id" class="w-full px-4 py-2 rounded-lg border mb-4" onchange="this.form.submit()">
                                    <option value="">Select City</option>
                                    @foreach ($cities as $item)
                                    <option @if($item['city_id'] == $city_id) selected @endif value="{{ $item['city_id'] }}">{{ $item['city_name'] }}</option>    
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        
                        <div class="lg:flex lg:gap-2 w-full">
                            @if(count($districts) > 0)
                            <div class="w-full flex flex-col gap-2">
                                <h3 class="font-bold">District</h3>
                                <select name="district_id" id="district_id" class="w-full px-4 py-2 rounded-lg border mb-4" onchange="this.form.submit()">
                                    <option value="">Select District</option>
                                    @foreach ($districts as $item)
                                    <option @if($item['district_id'] == $district_id) selected @endif value="{{ $item['district_id'] }}">{{ $item['district_name'] }}</option>    
                                    @endforeach
                                </select>
                            </div>
                            @endif
                            @if(count($subdistricts) > 0)
                            <div class="w-full flex flex-col gap-2">
                                <h3 class="font-bold">Subdistrict</h3>
                                <select name="subdistrict_id" id="subdistrict_id" class="w-full px-4 py-2 rounded-lg border mb-4" onchange="this.form.submit()">
                                    <option value="">Select Subdistrict</option>
                                    @foreach ($subdistricts as $item)
                                    <option @if($item['subdistrict_id'] == $subdistrict_id) selected @endif value="{{ $item['subdistrict_id'] }}">{{ $item['subdistrict_name'] }}</option>    
                                    @endforeach
                                </select>
                            </div>
                            @endif
                        </div>
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
            var initialLat = devices != null && devices.length > 0 ? devices[0].lat : defaultLat;
            var initialLng = devices != null && devices.length > 0 ? devices[0].lng : defaultLng;

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

            if(devices != null){
                // Menambahkan marker untuk setiap perangkat
                devices.forEach(function (device) {
                    if (device.lat && device.lng) { // Pastikan lat dan lng ada
                        var position = [parseFloat(device.lat), parseFloat(device.lng)];

                        // Tentukan ikon berdasarkan sumber data
                        var iconUrl = '{{ asset('asset/img/Icon/router_12068565.png') }}';

                        // Konfigurasi ikon marker
                        var icon = L.icon({
                            iconUrl: iconUrl,
                            iconSize: [32, 32], // Ukuran ikon bisa disesuaikan
                            iconAnchor: [16, 32] // Atur posisi anchor agar ikon terlihat bagus
                        });

                        // Tambahkan posisi ke bounds
                        bounds.push(position);

                        // Buat marker
                        var marker = L.marker(position, { icon: icon }).addTo(map);

                        // Buat konten popup dinamis
                        var popupContent = `
                            <div>
                                <strong>Device Name:</strong> ${device.device_name}<br>
                                <strong>Device Number:</strong> ${device.device_no}<br>
                                <strong>Address:</strong> ${device.address}<br>
                                <strong>Point Code:</strong> ${device.point_code}<br>
                                <strong>Location Info:</strong> ${device.location_information}<br>
                                <strong>Note:</strong> ${device.note}<br>
                                <strong>Surrounding Waters:</strong> ${device.surrounding_waters}<br>
                                <strong>Electrical Panel:</strong> ${device.electrical_panel}<br>
                            </div>
                        `;

                        // Tambahkan event hover (mouseover dan mouseout)
                        marker.on('mouseover', function () {
                            marker.bindPopup(popupContent).openPopup();
                        });

                        marker.on('mouseout', function () {
                            marker.closePopup();
                        });

                        // Tambahkan event click untuk menampilkan detail
                        marker.on('click', function () {
                            showDetail(device.id);
                        });
                    }
                });
            }


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
