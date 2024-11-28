@extends('layouts.app')

@section('content')
    <div x-data="{ sidebar: true, popupNavbar: false }" class="relative flex">
        <div class="absolute w-full h-[250px] bg-[#083C76] -z-10">

        </div>
        <div class="min-h-screen w-full">
            @include('components.navbar')
            <div class="mx-auto max-h-screen overflow-auto">
                <div class="px-4">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-2xl font-semibold text-gray-800 mb-4 text-white">Dashboard</h2>
                    </div>
                </div>

                <div class="overflow-x-auto bg-white px-6 py-10 rounded-lg shadow-lg">
                    <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-8 gap-4">
                        @foreach ($all_devices as $device)
                        <a href="{{ route('device.show', ['id' => $device['device_id']]) }}" class="{{ $device['is_line'] == 1 ? 'bg-[#083C76]' : 'bg-gray-500' }} py-2 rounded cursor-pointer hover:scale-105 duration-200 shadow-lg">
                            <div class="text-gray-300 text-[12px] px-2 truncate">
                                {{ $device['device_name'] ?? '-' }}
                            </div>
                            <div class="text-gray-300 text-[12px] px-2 truncate">
                                {{ $device['point_code'] ?? '-' }}
                            </div>
                            <div class="text-gray-300 text-[12px] px-2 truncate">
                                {{ $device['group_name'] ?? '-' }}
                            </div>
                            <div class="text-white text-[12px] px-2 truncate mt-2">
                                {{ $device['sensor_name'] ?? '-' }}
                            </div>
                            <div class="pb-6 text-center text-[24px] text-[#E94F07]">
                                {{ $device['value'] ?? '-' }} {{ $device['unit'] ?? '-' }}
                            </div>
                            <div class="text-gray-300 text-[12px] px-2 truncate">
                                Updated At <br>
                                {{ $device['updated_at'] ? \Carbon\Carbon::parse($device['updated_at'])->format('d-m-Y H:i:s') : '-' }}
                            </div>
                        </a>
                        @endforeach
                    </div>                    
                </div>
            </div>
        </div>
    </div>
@endsection