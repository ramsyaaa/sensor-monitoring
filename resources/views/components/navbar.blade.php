<nav class="w-full p-4 flex items-center justify-end">
    <div class="relative flex items-center justify-between w-full">
        <div>
            <img src="{{ asset('asset/img/Icon/logo_bpbd-removebg-preview.png') }}" alt="" class="max-w-[50px]">
        </div>
        <div class="text-white flex gap-4">
            <a class="px-4 py-2 rounded-lg hover:bg-[#E94F07] hover:scale-110 duration-200 {{ request()->is('dashboard*') ? 'bg-[#E94F07] text-white' : '' }}" href="{{ route('device.index') }}"><span class="block md:hidden"><i class="fas fa-tachometer-alt"></i></span><span class="hidden md:block">Dashboard</span></a>
            <a class="px-4 py-2 rounded-lg hover:bg-[#E94F07] hover:scale-110 duration-200 {{ request()->is('devices*') ? 'bg-[#E94F07] text-white' : '' }}" href="{{ route('device.index') }}"><span class="block md:hidden"><i class="fas fa-thermometer-half"></i></span><span class="hidden md:block">Devices</span></a>
            <a class="px-4 py-2 rounded-lg hover:bg-[#E94F07] hover:scale-110 duration-200 {{ request()->is('maps*') ? 'bg-[#E94F07] text-white' : '' }}" href="{{ route('map.index') }}"><span class="block md:hidden"><i class="fas fa-map-marker-alt"></i></span><span class="hidden md:block">Location</span></a>
        </div>
        <div class="flex items-center gap-4">
            <img @click="popupNavbar=!popupNavbar" src="{{ asset('asset/img/blank-profile.png') }}" class="w-[40px] rounded-full shadow-lg cursor-pointer" alt="">
        </div>
        <div x-cloak @click.outside="popupNavbar=!popupNavbar" x-show="popupNavbar" style="z-index: 1000" class="absolute w-[200px] p-4 bg-white rounded-lg shadow-lg top-[50px] right-[20px]">
            <form action="{{ route('auth.logout') }}" method="POST" class="w-full">
                @csrf
                <button type="submit" class="w-full flex justify-center items-center bg-red-600 text-white px-4 py-2 rounded-lg mt-4">Logout</button>
            </form>
        </div>
    </div>
</nav>
