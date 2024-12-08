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
                                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
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
                                class: 'grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4'
                            });

                            // Iterasi devices
                            $.each(group, function(index, device) {console.log(device.point_code)
                                const $deviceLink = $('<a>', {
                                    href: `{{ route('device.show', ['id' => ':id']) }}`.replace(":id", device.id),
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

                                const $sensorList = $('<div>', {
                                    class: 'flex items-center justify-center gap-2'
                                });

                                const $divVelocity = $('<div>', {
                                    class: 'flex flex-col items-center gap-2'
                                });

                                const $flowVelocityName = $('<div>', {
                                    class: 'text-white text-[8px] px-2 truncate mt-2',
                                    text: device.flow_velocity_sensor_name || '-'
                                });

                                const $flowVelocityValue = $('<div>', {
                                    class: 'pb-6 text-center text-[10px] text-[#E94F07]',
                                    text: `${device.flow_velocity_value || '-'} ${device.flow_velocity_unit || '-'}`
                                });

                                $divVelocity.append($flowVelocityName, $flowVelocityValue);

                                const $divInstantaneous = $('<div>', {
                                    class: 'flex flex-col items-center gap-2'
                                });

                                const $instantaneousSensorName = $('<div>', {
                                    class: 'text-white text-[8px] px-2 truncate mt-2',
                                    text: device.instantaneous_flow_sensor_name || '-'
                                });

                                const $instantaneousValue = $('<div>', {
                                    class: 'pb-6 text-center text-[10px] text-[#E94F07]',
                                    text: `${device.instantaneous_flow_value || '-'} ${device.instantaneous_flow_unit || '-'}`
                                });

                                $divInstantaneous.append($instantaneousSensorName, $instantaneousValue);

                                const $divWaterLevel = $('<div>', {
                                    class: 'flex flex-col items-center gap-2'
                                });

                                const $waterLevelSensorName = $('<div>', {
                                    class: 'text-white text-[8px] px-2 truncate mt-2',
                                    text: device.water_level_sensor_name || '-'
                                });

                                const $waterLevelValue = $('<div>', {
                                    class: 'pb-6 text-center text-[10px] text-[#E94F07]',
                                    text: `${device.water_level_value || '-'} ${device.water_level_unit || '-'}`
                                });

                                $divWaterLevel.append($waterLevelSensorName, $waterLevelValue);

                                $sensorList.append($divWaterLevel, $divVelocity, $divInstantaneous);
                                $deviceLink.append($pointCode, $address, $sensorList);
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
