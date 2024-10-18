@extends('layouts.app')

@section('content')
<div x-data="{sidebar:true, popupNavbar:false}" class="relative flex">
    <div class="absolute w-full h-[250px] bg-[#14176c] -z-10">

    </div>
    @include('components.sidebar')
    <div class="min-h-screen" :class="sidebar ? 'w-10/12' : 'w-full'">
        @include('components.navbar')
        <div class="container mx-auto px-4 pt-4 pb-12 max-h-screen overflow-auto hide-scrollbar">
            @include('components.breadcrumb' ,[
                'lists' => [
                    [
                        'title' => 'Devices',
                        'route' => route('device.index'),
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
                <h2 class="text-2xl font-semibold text-gray-800 mb-4 text-white">Edit Device</h2>
            </div>
            <div class="overflow-x-auto bg-white rounded-lg px-6 py-10 shadow-lg">
                <div x-data="{ sensors: [], latitude: '', longitude: '' }">
                    <div class="space-y-4">
                        <div class="flex flex-col md:flex-row gap-4 items-center">
                            <label for="deviceGroup" class="font-semibold w-40">Device Group</label>
                            <select id="deviceGroup" class="flex-1 p-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="">Select Device Group</option>
                            </select>
                        </div>

                        <div class="flex flex-col md:flex-row gap-4 items-center">
                            <label for="device" class="font-semibold w-40">Device</label>
                            <select id="device" class="flex-1 p-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="">Select Device</option>
                            </select>
                        </div>

                        <div class="flex flex-col md:flex-row gap-4 items-center">
                            <label for="link" class="font-semibold w-40">Link</label>
                            <select id="link" class="flex-1 p-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="mb_rtu">MB RTU</option>
                            </select>
                        </div>

                        <div class="flex flex-col md:flex-row gap-4 items-center">
                            <label for="timeZone" class="font-semibold w-40">Time Zone</label>
                            <select id="timeZone" class="flex-1 p-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="utc_7">UTC+07:00</option>
                            </select>
                        </div>

                        <div class="flex flex-col md:flex-row gap-4 items-center">
                            <label for="dropping" class="font-semibold w-40">Dropping</label>
                            <div class="flex gap-2 items-center flex-1">
                                <select id="dropping" class="p-2 border border-gray-300 rounded-md flex-1 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <option value="custom">Custom</option>
                                </select>
                                <input type="text" class="p-2 border border-gray-300 rounded-md w-24 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Value">
                            </div>
                        </div>

                        <!-- Add Sensor Section -->
                        <div>
                            <div class="flex justify-between flex-col mb-2 gap-4">
                                <label class="font-semibold">Sensor</label>
                                <button type="button" @click="sensors.push({ name: '', type: 'Numerical Type', decimals: 2, unit: '', index: sensors.length + 1 })" class="w-fit bg-blue-500 text-white py-1 px-4 rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    Add Sensor
                                </button>
                            </div>

                            <template x-for="(sensor, index) in sensors" :key="index">
                                <div class="flex flex-wrap gap-2 mb-2 p-2 border border-gray-300 rounded-md">
                                    <input type="text" x-model="sensor.name" class="flex-1 p-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Sensor Name">
                                    <select x-model="sensor.type" class="p-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                        <option value="Numerical Type">Numerical Type</option>
                                    </select>
                                    <input type="number" x-model="sensor.decimals" class="w-20 p-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Decimals">
                                    <input type="text" x-model="sensor.unit" class="w-20 p-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Unit">
                                    <input type="number" x-model="sensor.index" class="w-12 p-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Index" readonly>
                                    <button type="button" @click="sensors.splice(index, 1)" class="bg-red-500 text-white py-1 px-2 rounded-md hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-500">
                                        Delete
                                    </button>
                                </div>
                            </template>
                        </div>

                        <!-- Latitude and Longitude Selection with Map -->
                        <div class="flex flex-col md:flex-row gap-4 items-center">
                            <label for="position" class="font-semibold w-40">Position</label>
                            <div class="flex gap-2 items-center flex-1">
                                <input type="text" x-model="latitude" placeholder="Latitude" class="p-2 border border-gray-300 rounded-md flex-1 focus:outline-none focus:ring-2 focus:ring-blue-500" readonly>
                                <input type="text" x-model="longitude" placeholder="Longitude" class="p-2 border border-gray-300 rounded-md flex-1 focus:outline-none focus:ring-2 focus:ring-blue-500" readonly>
                            </div>
                        </div>

                        <div id="map"></div>

                        <div class="flex justify-center mt-4">
                            <button type="button" class="bg-blue-500 text-white py-2 px-6 rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                Save Device
                            </button>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<script src="https://unpkg.com/alpinejs" defer></script>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const map = L.map('map').setView([-6.914744, 107.609810], 13); // Default center at Bandung

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);

        let marker = L.marker([-6.914744, 107.609810]).addTo(map);

        map.on('click', function(e) {
            const lat = e.latlng.lat;
            const lng = e.latlng.lng;
            marker.setLatLng([lat, lng]);
            document.querySelector('input[x-model="latitude"]').value = lat;
            document.querySelector('input[x-model="longitude"]').value = lng;
        });
    });
</script>

@endsection
