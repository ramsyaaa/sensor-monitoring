@extends('layouts.app')

@section('content')
<div x-data="{sidebar:true, popupNavbar:false}" class="relative flex">
    <div class="absolute w-full h-[250px] bg-[#083C76] -z-10">

    </div>
    <div class="min-h-screen w-full">
        @include('components.navbar')
        <div class="mx-auto pt-4 pb-12 max-h-screen overflow-auto hide-scrollbar">
            <div class="px-4">
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
            </div>
            <div class="overflow-x-auto bg-white rounded-lg px-6 py-10 shadow-lg">
                <div class="flex gap-2 mb-4">
                    <div class="hover:scale-105 duration-200">
                        <a href="{{ route('device.index') }}" class="px-4 py-2 rounded-lg text-white bg-red-500 shadow-lg">Back</a>
                    </div>
                </div>
                <form action="{{ route('device.update', ['id' => $device['id']]) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div x-data="{ lat: '', lng: '' }">
                        <div class="space-y-4">
                            <div class="flex flex-col md:flex-row gap-4 items-center">
                                <label for="point_code" class="font-semibold w-40">City</label>
                                <div class="flex flex-col gap-2 flex-1">
                                    <select name="city_id" id="city_id" class="p-2 border border-gray-300 rounded-md w-full focus:outline-none focus:ring-2 focus:ring-blue-500">
                                        <option value="">Select City</option>
                                        @foreach ($cities as $city)
                                            <option @if($city['city_id'] == $device['city_id']) selected @endif value="{{ $city['city_id'] }}">{{ $city['city_name'] }}</option>
                                        @endforeach
                                    </select>
                                    @error('city_id')
                                        <div class="text-red-500 text-[10px]">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>

                            <div class="flex flex-col md:flex-row gap-4 items-center">
                                <label for="point_code" class="font-semibold w-40">District</label>
                                <div class="flex flex-col gap-2 flex-1">
                                    <select name="district_id" id="district_id" class="p-2 border border-gray-300 rounded-md w-full focus:outline-none focus:ring-2 focus:ring-blue-500">
                                        <option value="">Select District</option>
                                    </select>
                                    @error('district_id')
                                        <div class="text-red-500 text-[10px]">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>

                            <div class="flex flex-col md:flex-row gap-4 items-center">
                                <label for="point_code" class="font-semibold w-40">Subdistrict</label>
                                <div class="flex flex-col gap-2 flex-1">
                                    <select name="subdistrict_id" id="subdistrict_id" class="p-2 border border-gray-300 rounded-md w-full focus:outline-none focus:ring-2 focus:ring-blue-500">
                                        <option value="">Select Subdistrict</option>
                                    </select>
                                    @error('subdistrict_id')
                                        <div class="text-red-500 text-[10px]">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>

                            <div class="flex flex-col md:flex-row gap-4 items-center">
                                <label for="point_code" class="font-semibold w-40">Point Code</label>
                                <div class="flex flex-col gap-2 flex-1">
                                    <input type="text" value="{{ old('point_code', $device['point_code']) }}" name="point_code" id="point_code" class="p-2 border border-gray-300 rounded-md w-full focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Value">
                                    @error('point_code')
                                        <div class="text-red-500 text-[10px]">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
    
                            <div class="flex flex-col md:flex-row gap-4 items-center">
                                <label for="address" class="font-semibold w-40">Address</label>
                                <div class="flex gap-2 flex-col flex-1">
                                    <input type="text" id="address" value="{{ old('address', $device['address']) }}" name="address" class="p-2 border border-gray-300 rounded-md w-full focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Value">
                                    @error('address')
                                        <div class="text-red-500 text-[10px]">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
    
                            <div class="flex flex-col md:flex-row gap-4 items-center">
                                <label for="electrical_panel" class="font-semibold w-40">Electrical Panel</label>
                                <div class="flex gap-2 flex-col flex-1">
                                    <input type="text" id="electrical_panel" value="{{ old('electrical_panel', $device['electrical_panel']) }}" name="electrical_panel" class="p-2 border border-gray-300 rounded-md w-full focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Value">
                                    @error('electrical_panel')
                                        <div class="text-red-500 text-[10px]">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
    
                            <div class="flex flex-col md:flex-row gap-4 items-center">
                                <label for="surrounding_waters" class="font-semibold w-40">Surrounding Waters</label>
                                <div class="flex gap-2 flex-col flex-1">
                                    <input type="text" id="surrounding_waters" value="{{ old('surrounding_waters', $device['surrounding_waters']) }}" name="surrounding_waters" class="p-2 border border-gray-300 rounded-md w-full focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Value">
                                    @error('surrounding_waters')
                                        <div class="text-red-500 text-[10px]">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
    
                            <div class="flex flex-col md:flex-row gap-4 items-center">
                                <label for="location_information" class="font-semibold w-40">Location Information</label>
                                <div class="flex gap-2 flex-col flex-1">
                                    <textarea name="location_information" id="location_information" class="p-2 border border-gray-300 rounded-md w-full focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('location_information', $device['location_information']) }}</textarea>
                                    @error('location_information')
                                        <div class="text-red-500 text-[10px]">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
    
                            <div class="flex flex-col md:flex-row gap-4 items-center">
                                <label for="note" class="font-semibold w-40">Note</label>
                                <div class="flex gap-2 flex-col flex-1">
                                    <input type="text" id="note" value="{{ old('note', $device['note']) }}" name="note" class="p-2 border border-gray-300 rounded-md w-full focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Value">
                                    @error('note')
                                        <div class="text-red-500 text-[10px]">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
    
                            <!-- lat and lng Selection with Map -->
                            <div class="flex flex-col md:flex-row gap-4 items-center">
                                <label for="position" class="font-semibold w-40">Position</label>
                                <div class="flex gap-2 items-center flex-1">
                                    <div class="w-full flex flex-col gap-2">
                                        <input type="text" value="{{ old('lat', $device['lat']) }}" name="lat" x-model="lat" placeholder="latitude" class="p-2 border border-gray-300 rounded-md flex-1 focus:outline-none focus:ring-2 focus:ring-blue-500" readonly>
                                        @error('lat')
                                            <div class="text-red-500 text-[10px]">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                    <div class="w-full flex flex-col gap-2">
                                        <input type="text" value="{{ old('lng', $device['lng']) }}" name="lng" x-model="lng" placeholder="longitude" class="p-2 border border-gray-300 rounded-md flex-1 focus:outline-none focus:ring-2 focus:ring-blue-500" readonly>
                                        @error('lng')
                                        <div class="text-red-500 text-[10px]">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                    </div>
                                </div>
                            </div>
    
                            <div id="map"></div>
    
                            <div class="flex justify-center mt-4">
                                <button type="submit" class="bg-blue-500 text-white py-2 px-6 rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    Save Device
                                </button>
                            </div>
    
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<script src="https://unpkg.com/alpinejs" defer></script>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        // Data lat dan lng dari server (gunakan null jika tidak ada data)
        const defaultLat = "{{ $device['lat'] ?? '-6.914744' }}";
        const defaultLng = "{{ $device['lng'] ?? '107.609810' }}";
        
        // Inisialisasi peta dengan posisi default
        const map = L.map('map').setView([defaultLat, defaultLng], 18); 

        // Tambahkan layer peta
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);

        // Tambahkan marker pada posisi default
        let marker = L.marker([defaultLat, defaultLng]).addTo(map);
        document.querySelector('input[x-model="lat"]').value = "{{ $device['lat'] ?? '' }}";
        document.querySelector('input[x-model="lng"]').value = "{{ $device['lng'] ?? '' }}";

        // Update marker dan input lat/lng saat peta di-klik
        map.on('click', function(e) {
            const lat = e.latlng.lat;
            const lng = e.latlng.lng;
            marker.setLatLng([lat, lng]);
            document.querySelector('input[x-model="lat"]').value = lat;
            document.querySelector('input[x-model="lng"]').value = lng;
        });

        const cityId = {{ old('city_id', $device['city_id']) ?? 'null' }}; // Masukkan city_id dari server-side
        if (cityId) {
            loadDistricts(cityId); // Panggil fungsi untuk memuat distrik
        }

        const districtId = {{ old('district_id', $device['district_id']) ?? 'null' }}; // Masukkan city_id dari server-side
        if (districtId) {
            loadSubdistricts(districtId); // Panggil fungsi untuk memuat distrik
        }

        const citySelect = document.getElementById('city_id');

        citySelect.addEventListener('change', (event) => {
            const cityId = event.target.value; // Ambil city_id dari opsi yang dipilih
            loadDistricts(cityId); // Panggil fungsi loadDistricts dengan cityId
        });

        const districtSelect = document.getElementById('district_id');

        districtSelect.addEventListener('change', (event) => {
            const districtId = event.target.value; // Ambil district_id dari opsi yang dipilih
            loadSubdistricts(districtId); // Panggil fungsi loadDistricts dengan districtId
        });

        function loadDistricts(cityId) {
            const district_id = document.getElementById('district_id'); // Pastikan ada elemen <select> dengan id ini.
            const deviceDistrictId = {{ old('district_id', $device['district_id']) ?? 'null' }};

            // Kosongkan isi select sebelum memuat data baru
            district_id.innerHTML = '<option value="">Select District</option>';

            // Pastikan cityId tersedia
            if (!cityId) {
                console.error('City ID is missing');
                return;
            }

            // Lakukan request ke endpoint
            fetch(`/territory/district/${cityId}`)
                .then((response) => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json(); // Parsing response JSON
                })
                .then((data) => {
                    if (Array.isArray(data.districts) && data.districts.length > 0) {
                        // Bersihkan opsi yang ada di select sebelumnya
                        district_id.innerHTML = '<option value="">Select District</option>';

                        data.districts.forEach((district) => {
                            const option = document.createElement('option');
                            option.value = district.district_id;
                            option.textContent = district.district_name;

                            // Periksa apakah district_id sama dengan $device['district_id']
                            if (district.district_id === parseInt(deviceDistrictId)) {
                                option.selected = true; // Tandai opsi sebagai terpilih
                                loadSubdistricts(district.district_id);
                            }

                            district_id.appendChild(option);
                        });
                    } else {
                        console.warn('No districts found for the given city ID.');
                    }
                })
                .catch((error) => {
                    console.error('Error fetching districts:', error);
                });
        }

        function loadSubdistricts(districtId) {
            const subdistrict_id = document.getElementById('subdistrict_id'); // Pastikan ada elemen <select> dengan id ini.
            const deviceSubdistrictId = {{ old('subdistrict_id', $device['subdistrict_id']) ?? 'null' }};

            // Kosongkan isi select sebelum memuat data baru
            subdistrict_id.innerHTML = '<option value="">Select Subdistrict</option>';

            // Pastikan districtId tersedia
            if (!districtId) {
                console.error('District ID is missing');
                return;
            }

            // Lakukan request ke endpoint
            fetch(`/territory/subdistrict/${districtId}`)
                .then((response) => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json(); // Parsing response JSON
                })
                .then((data) => {
                    if (Array.isArray(data.subdistricts) && data.subdistricts.length > 0) {
                        // Bersihkan opsi yang ada di select sebelumnya
                        subdistrict_id.innerHTML = '<option value="">Select Subdistrict</option>';

                        data.subdistricts.forEach((subdistrict) => {
                            const option = document.createElement('option');
                            option.value = subdistrict.subdistrict_id;
                            option.textContent = subdistrict.subdistrict_name;
                            // Periksa apakah subdistrict_id sama dengan $device['subdistrict_id']
                            if (subdistrict.subdistrict_id === parseInt(deviceSubdistrictId)) {
                                option.selected = true; // Tandai opsi sebagai terpilih
                            }

                            subdistrict_id.appendChild(option);
                        });
                    } else {
                        console.warn('No subdistricts found for the given district ID.');
                    }
                })
                .catch((error) => {
                    console.error('Error fetching subdistricts:', error);
                });
        }

    });
</script>


@endsection
