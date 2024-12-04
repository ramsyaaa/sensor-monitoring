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

                <div id="dashboard-content">
                    <div class="overflow-x-auto bg-white px-6 py-10 rounded-lg shadow-lg">
                        <div class="container mx-auto mb-4">
                            <h1 class="text-2xl font-bold text-center mb-6">Device Overview</h1>
                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                                @for ($i = 0; $i < 6; $i++)
                                <div class="bg-white shadow-lg rounded-lg p-6 text-center animate-pulse">
                                    <div class="h-6 bg-gray-300 rounded w-3/4 mx-auto mb-4"></div>
                                    <div class="h-8 bg-gray-300 rounded w-1/2 mx-auto"></div>
                                </div>
                                @endfor
                            </div>
                        </div>
    
    
                        @for ($i = 0; $i < 3; $i++)
                            <div class="flex flex-col gap-4 mb-8 p-4 bg-gray-200 rounded-lg shadow-lg animate-pulse">
                                <div class="h-6 bg-gray-300 rounded w-1/4 mb-4"></div>
                                <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-8 gap-4">
                                    @for ($j = 0; $j < 8; $j++)
                                    <div class="bg-gray-300 rounded p-4 shadow-lg">
                                        <div class="h-4 bg-gray-400 rounded mb-2"></div>
                                        <div class="h-4 bg-gray-400 rounded mb-2"></div>
                                        <div class="h-4 bg-gray-400 rounded mb-2"></div>
                                        <div class="h-6 bg-gray-400 rounded mt-4"></div>
                                    </div>
                                    @endfor
                                </div>
                            </div>
                        @endfor
                    </div>
                </div>
                {{-- <div class="overflow-x-auto bg-white px-6 py-10 rounded-lg shadow-lg">
                    @foreach ($data_overview_device as $index => $data)
                            <div class="bg-white shadow-lg rounded-lg p-6 text-center">
                                <h2 class="text-lg font-bold text-gray-700">Total Online {{ $index }}</h2>
                                <p class="text-2xl font-bold text-blue-500 mt-2">{{ $data['active'] }} / {{ $data['total'] }}</p>
                            </div>
                            @endforeach


                    @foreach ($all_devices as $key => $group)
                    <div class="flex flex-col gap-4 mb-8 p-4 bg-gray-200 rounded-lg shadow-lg">
                        <div class="font-bold text-[16px]">
                            {{ $key }}
                        </div>
                        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-8 gap-4">
                            @foreach ($group as $device)
                            <a href="{{ route('device.show', ['id' => $device['device_id']]) }}" class="{{ $device['is_line'] == 1 ? 'bg-[#083C76]' : 'bg-gray-500' }} py-2 rounded cursor-pointer hover:scale-105 duration-200 shadow-lg">
                                <div class="text-gray-300 text-[12px] px-2 truncate">
                                    {{ $device['point_code'] ?? '-' }}
                                </div>
                                <div class="text-gray-300 text-[12px] px-2 truncate">
                                    {{ $device['address'] ?? '-' }}
                                </div>
                                <div class="text-white text-[12px] px-2 truncate mt-2">
                                    {{ $device['sensor_name'] ?? '-' }}
                                </div>
                                <div class="pb-6 text-center text-[24px] text-[#E94F07]">
                                    {{ $device['value'] ?? '-' }} {{ $device['unit'] ?? '-' }}
                                </div>
                            </a>
                            @endforeach
                        </div> 
                    </div>
                    @endforeach                   
                </div> --}}
            </div>
        </div>
    </div>
    
    <script>
        $(document).ready(function() {
            getDashboard();

            // Set interval untuk memanggil getDashboard setiap 2 menit (120000 ms)
            setInterval(getDashboard, 120000);
        });



        function getDashboard() {
            $.ajax({
                url: "/api/dashboard",
                method: "GET",
                dataType: "json",
                headers: {
                    "Content-Type": "application/json",
                    "Accept": "application/json",
                },
                success: function(data) {
                    console.log("Data fetched:", data);

                    // Pastikan data berhasil diambil
                    if (data.success) {
                        const $dashboardContent = $('#dashboard-content');

                        // Hapus semua child elemen dari dashboard-content
                        $dashboardContent.empty();

                        const $bgOverview = $('<div>', {
                            class: 'overflow-x-auto bg-white px-6 py-10 rounded-lg shadow-lg'
                        });

                        // Overview Section
                        const $overviewContainer = $('<div>', {
                            class: 'container mx-auto mb-4'
                        });

                        const $overviewTitle = $('<h1>', {
                            class: 'text-2xl font-bold text-center mb-6',
                            text: 'Device Overview'
                        });

                        const $overviewGrid = $('<div>', {
                            class: 'grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6'
                        });

                        // Iterasi data_overview_device untuk overview
                        $.each(data.data.data_overview_device, function(region, overview) {
                            const $card = $('<div>', {
                                class: 'bg-white shadow-lg rounded-lg p-6 text-center'
                            });

                            const $cardTitle = $('<h2>', {
                                class: 'text-lg font-bold text-gray-700',
                                text: `Total Online ${region}`
                            });

                            const $cardCount = $('<p>', {
                                class: 'text-2xl font-bold text-blue-500 mt-2',
                                text: `${overview.active} / ${overview.total}`
                            });

                            $card.append($cardTitle, $cardCount);
                            $overviewGrid.append($card);
                        });

                        $overviewContainer.append($overviewTitle, $overviewGrid);
                        $bgOverview.append($overviewContainer);
                        $dashboardContent.append($bgOverview);

                        // Devices Section
                        $.each(data.data.all_devices, function(region, group) {
                            const $groupContainer = $('<div>', {
                                class: 'flex flex-col gap-4 mb-8 p-4 bg-gray-200 rounded-lg shadow-lg'
                            });

                            const $groupTitle = $('<div>', {
                                class: 'font-bold text-[16px]',
                                text: region
                            });

                            const $deviceGrid = $('<div>', {
                                class: 'grid grid-cols-2 md:grid-cols-4 lg:grid-cols-8 gap-4'
                            });

                            // Iterasi devices
                            $.each(group, function(index, device) {
                                const $deviceLink = $('<a>', {
                                    href: `{{ route('device.show', ['id' => ':id']) }}`.replace(":id", device.device_id),
                                    class: (device.is_line === 1 ? 'bg-[#083C76]' : 'bg-gray-500') + ' py-2 rounded cursor-pointer hover:scale-105 duration-200 shadow-lg'
                                });

                                const $pointCode = $('<div>', {
                                    class: 'text-gray-300 text-[12px] px-2 truncate',
                                    text: device.point_code || '-'
                                });

                                const $address = $('<div>', {
                                    class: 'text-gray-300 text-[12px] px-2 truncate',
                                    text: device.address || '-'
                                });

                                const $sensorName = $('<div>', {
                                    class: 'text-white text-[12px] px-2 truncate mt-2',
                                    text: device.sensor_name || '-'
                                });

                                const $value = $('<div>', {
                                    class: 'pb-6 text-center text-[24px] text-[#E94F07]',
                                    text: `${device.value || '-'} ${device.unit || '-'}`
                                });

                                $deviceLink.append($pointCode, $address, $sensorName, $value);
                                $deviceGrid.append($deviceLink);
                            });

                            $groupContainer.append($groupTitle, $deviceGrid);
                            $bgOverview.append($groupContainer);
                            $dashboardContent.append($bgOverview);
                        });

                    } else {
                        console.error('API response error: ', data);
                    }
                },
                error: function(xhr, status, error) {
                    console.error("Error fetching data:", error);
                }
            });
        }



    </script>
    
@endsection
