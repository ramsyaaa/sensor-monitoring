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
                        @for ($i = 0; $i < 91; $i++)
                        <a href="#" class="bg-[#083C76] py-2 rounded cursor-pointer hover:scale-105 duration-200 shadow-lg">
                            <div class="text-gray-300 text-[12px] px-2 truncate">
                                HK301-DTU-HPI2403H0120239
                            </div>
                            <div class="text-white text-[12px] px-2 truncate mt-2">
                                Water level
                            </div>
                            <div class="pb-6 text-white text-center text-[24px]">
                                123 m/s
                            </div>
                        </a>
                        @endfor
                    </div>                    
                </div>
            </div>
        </div>
    </div>
@endsection
