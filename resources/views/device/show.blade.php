@extends('layouts.app')

@section('content')
    <div x-data="{sidebar:true, popupNavbar:false}" class="relative flex">
        <div class="absolute w-full h-[250px] bg-[#083C76] -z-10">

        </div>
        <div class="min-h-screen w-full">
            @include('components.navbar')
            <div class="mx-auto max-h-screen overflow-auto">
                <div class="px-4">
                    @include('components.breadcrumb' ,[
                        'lists' => [
                            [
                                'title' => 'Devices',
                                'route' => route('device.index'),
                                'is_active' => false
                            ],
                            [
                                'title' => 'Detail',
                                'route' => '#',
                                'is_active' => true
                            ]
                        ]
                    ])
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-2xl font-semibold text-gray-800 mb-4 text-white">Data Sensor Device</h2>
                    </div>
                </div>
                <div class="overflow-x-auto bg-white px-6 pb-10 rounded-lg shadow-lg">
                    <div class="flex flex-col gap-2 mt-4 items-start">
                        <div class="hover:scale-105 duration-200">
                            <a href="{{ route('device.index') }}" class="px-4 py-2 rounded-lg text-white bg-red-500 shadow-lg">Back</a>
                        </div>
                        <div class="py-5 font-bold text-[20px]">
                            Device Name:{{ $data[0]['device_name'] }} <br>
                            Point Code: {{$data[0]['point_code']}}<br>
                            Address: {{$data[0]['address']}}<br>
                        </div>
                    </div>
                    <table class="min-w-full border border-gray-300 rounded-lg shadow-md">
                        <thead class="bg-gray-200 text-gray-600 uppercase text-sm leading-normal">
                            <tr class="text-[12px]">
                                <th class="py-3 px-6 text-left">No</th>
                                <th class="py-3 px-6 text-center">Sensor</th>
                                <th class="py-3 px-6 text-left">Value</th>
                                <th class="py-3 px-6 text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-700 text-sm font-light">
                            @if ($sensors != null && count($sensors) > 0)
                            @php
                                $i = 1;
                            @endphp
                                @foreach ($sensors as $index => $sensor)
                                @if($sensor['sensor_name'] == 'Atmosphere' || $sensor['sensor_name'] == 'Noise')
                                    @continue
                                @endif
                                <tr class="border-b border-gray-200 hover:bg-gray-100 {{ $sensor['is_line'] == 0 ? 'text-gray-300' : 'text-black' }}">
                                    <td class="py-3 px-6 text-left">{{ $i }}</td>
                                    <td class="py-3 px-6 flex gap-2 items-center">
                                        <div class="w-[10px] h-[10px] {{ $sensor['is_line'] == 1 ? 'bg-green-500' : 'bg-red-500' }} rounded-full">
                                        </div>
                                        <div class="text-left flex gap-4 items-start">
                                            <div>
                                                <img class="max-w-[40px]" src="{{ checkUrlIcon($sensor['sensor_name']) }}" alt="">
                                            </div>
                                            <div>
                                                <span class="font-bold text-[16px]">{{ $sensor['sensor_name'] }}</span>
                                                <br>
                                                <span class="text-[12px]">ID:{{ $sensor['id'] }}</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-3 px-6 text-left">
                                        <span class=" text-[20px]">@if($sensor['sensor_name'] == 'Wind Direction') {{ getWindDirection($sensor['value']) }} @else {{ $sensor['value'] }} @endif {{ $sensor['unit'] }}</span>
                                        <br>
                                        <span>Updated {{ $sensor['update_date'] }}</span>
                                    </td>
                                    <td class="py-3 px-6">
                                        <div class="flex gap-2 justify-center">
                                            <a title="Realtime Curv" href="javascript:void(0)" onclick="getRealtime({{ $sensor['id'] }})" 
                                                class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600 transition duration-200 flex items-center gap-2">
                                                <i class="fas fa-chart-line"></i>
                                            </a>
                                            @php
                                                $role = session('role');
                                            @endphp

                                            @if ($role === 'admin')
                                            <a title="Edit Sensor" href="{{ route('sensor.edit', ['id' => $sensor['id'], 'device' => $data[0]['id'] ]) }}" 
                                                class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 transition duration-200 flex items-center gap-2">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            @endif

                                        </div>
                                    </td>
                                </tr>
                                @php
                                    $i += 1;
                                @endphp
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @include('components.chart')
    </div>
@endsection
