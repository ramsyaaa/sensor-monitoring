@extends('layouts.app')

@section('content')
    <div x-data="{sidebar:true, popupNavbar:false}" class="relative flex">
        <div class="absolute w-full h-[250px] bg-[#083C76] -z-10">
        </div>
        <div class="min-h-screen w-full">
            @include('components.navbar')
            <div class="container mx-auto p-4 max-h-screen overflow-auto">
                @include('components.breadcrumb' ,[
                    'lists' => [
                        [
                            'title' => 'Devices',
                            'route' => route('device.index'),
                            'is_active' => false
                        ],
                        [
                            'title' => 'Sensors',
                            'route' => route('device.show', ['id' => $device_id]),
                            'is_active' => false
                        ],
                        [
                            'title' => 'Edit',
                            'route' => '#',
                            'is_active' => true
                        ]
                    ]
                ])
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-2xl font-semibold text-gray-800 mb-4 text-white">Data Sensor Device</h2>
                </div>

                <div class="overflow-x-auto bg-white px-6 pb-10 rounded-lg shadow-lg">
                    <div>
                        <form action="{{ route('sensor.update', ['id' => $id]) }}" method="POST">
                            @csrf
                            @method('PUT')
                    
                            <div class="flex flex-col gap-2 py-4">
                                <div class="flex flex-col gap-2">
                                    <label>Sensor Name:</label>
                                    <input type="text" value="{{ $sensor['sensor_name'] ?? '' }}" disabled class="border px-4 py-2 rounded-lg"/>
                                </div>
                                <div class="flex flex-col gap-2">
                                    <label for="latitude">Latitude:</label>
                                    <input type="text" id="latitude" name="latitude" value="{{ old('latitude', $sensor['lat']) }}" class="border px-4 py-2 rounded-lg"/>
                                    @error('latitude')
                                    <div class="text-red-500">
                                        {{ $message }}
                                    </div>                                        
                                    @enderror
                                </div>
                    
                                <div class="flex flex-col gap-2">
                                    <label for="longitude">Longitude:</label>
                                    <input type="text" id="longitude" name="longitude" value="{{ old('longitude', $sensor['lng']) }}" class="border px-4 py-2 rounded-lg"/>
                                    @error('longitude')
                                    <div class="text-red-500">
                                        {{ $message }}
                                    </div>                                        
                                    @enderror
                                </div>
                            </div>
                    
                            <!-- Elemen div untuk peta -->
                            <div id="map" style="width: 100%; height: 400px;"></div>
                    
                            <button type="submit" class="mt-4 px-4 py-2 text-white rounded-lg bg-blue-500">Update</button>
                        </form>
                    
                        <script>
                            document.addEventListener("DOMContentLoaded", function () {
                                // Mengambil data latitude dan longitude dari variabel PHP
                                let initialLat = {{ $sensor['lat'] ?? '' }}; // Ganti dengan latitude yang sesuai
                                let initialLng = {{ $sensor['lng'] ?? '' }}; // Ganti dengan longitude yang sesuai
                        
                                // Inisialisasi peta menggunakan Leaflet
                                var map = L.map('map').setView([initialLat, initialLng], 17);
                        
                                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                                    maxZoom: 19,
                                }).addTo(map);
                        
                                // Marker untuk menunjukkan lokasi saat ini
                                var marker = L.marker([initialLat, initialLng], { draggable: true }).addTo(map);
                        
                                // Fungsi untuk mengupdate input form dari posisi marker
                                function updateInputFields(lat, lng) {
                                    document.getElementById('latitude').value = lat;
                                    document.getElementById('longitude').value = lng;
                                }
                        
                                // Event Listener untuk sinkronisasi dari peta ke form input
                                map.on('click', function (e) {
                                    let lat = e.latlng.lat;
                                    let lng = e.latlng.lng;
                                    marker.setLatLng([lat, lng]); // Pindahkan marker ke posisi baru
                                    updateInputFields(lat, lng); // Update input fields
                                });
                        
                                // Event Listener untuk sinkronisasi dari marker (drag) ke form input
                                marker.on('dragend', function (e) {
                                    let lat = marker.getLatLng().lat;
                                    let lng = marker.getLatLng().lng;
                                    updateInputFields(lat, lng); // Update input fields saat marker dipindahkan
                                });
                        
                                // Event Listener untuk sinkronisasi dari input fields ke peta
                                document.getElementById('latitude').addEventListener('input', function () {
                                    let lat = parseFloat(this.value);
                                    let lng = parseFloat(document.getElementById('longitude').value);
                                    if (!isNaN(lat) && !isNaN(lng)) {
                                        marker.setLatLng([lat, lng]); // Pindahkan marker
                                        map.setView([lat, lng], map.getZoom()); // Ubah posisi peta
                                    }
                                });
                        
                                document.getElementById('longitude').addEventListener('input', function () {
                                    let lat = parseFloat(document.getElementById('latitude').value);
                                    let lng = parseFloat(this.value);
                                    if (!isNaN(lat) && !isNaN(lng)) {
                                        marker.setLatLng([lat, lng]); // Pindahkan marker
                                        map.setView([lat, lng], map.getZoom()); // Ubah posisi peta
                                    }
                                });
                            });
                        </script>
                    </div>
                </div>
            </div>
        </div>
        @include('components.chart')
    </div>
@endsection
