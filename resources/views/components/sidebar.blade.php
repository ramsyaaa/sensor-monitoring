<div
    x-show="sidebar"
    x-transition:enter="transition-transform transition-opacity ease-out duration-300"
    x-transition:enter-start="transform -translate-x-full opacity-0"
    x-transition:enter-end="transform translate-x-0 opacity-100"
    class="w-2/12 min-h-screen overflow-auto"
>
    <div class="mt-16">
        <div class="p-4 bg-white h-full min-h-[600px] rounded-r-xl shadow-lg">
            <div class="hidden lg:flex w-full gap-4 p-2 bg-gray-100 rounded-lg mb-4">
                <div class="">
                    <img src="{{ asset('asset/img/blank-profile.png') }}" class="w-[40px] rounded-full" alt="">
                </div>
                <div class="flex flex-col max-w-full">
                    <div class="text-[16px] font-bold truncate">
                        Admin
                    </div>
                    <div class="text-[12px] truncate">
                        Administrator
                    </div>
                </div>
            </div>
            <div>
                <div class="hidden lg:block mb-2 font-bold text-[16px] mt-8">
                    Monitoring
                </div>
                @include('components.list-sidebar', [
                    'route' => route('device.index'),
                    'title' => 'Device List',
                    'activePattern' => 'device*',
                    'icon' => 'fas fa-chart-line',
                ])
                @include('components.list-sidebar', [
                    'route' => route('map.index'),
                    'title' => 'Device Mapping',
                    'activePattern' => 'maps*',
                    'icon' => 'fas fa-map',
                ])
            </div>
        </div>
    </div>
</div>
