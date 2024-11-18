<a href="{{ $route }}">
    <div class="flex gap-4 md:gap-0 mb-4 p-2 text-[14px] w-full max-w-full border-b border-white rounded-lg {{ request()->is($activePattern) ? 'bg-[#E94F07] text-white' : 'text-gray-600' }} hover:bg-[#E94F07] hover:text-white duration-200">
        <div class="w-full md:w-2/12 flex md:justify-start justify-center items-center">
            <i class="{{ $icon }} w-[16px]"></i>
        </div>
        <div class="hidden md:block truncate">
            {{ $title }}
        </div>
    </div>
</a>
