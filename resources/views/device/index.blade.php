@extends('layouts.app')

@section('content')
    <div x-data="{sidebar:true, popupNavbar:false}" class="relative flex">
        <div class="absolute w-full h-[250px] bg-[#14176c] -z-10">

        </div>
        @include('components.sidebar')
        <div class="min-h-screen" :class="sidebar ? 'w-10/12' : 'w-full'">
            @include('components.navbar')
            <div class="container mx-auto p-4 max-h-screen overflow-auto">
                @include('components.breadcrumb' ,[
                    'lists' => [
                        [
                            'title' => 'Devices',
                            'route' => '#',
                            'is_active' => true
                        ]
                    ]
                ])
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-2xl font-semibold text-gray-800 mb-4 text-white">Daftar Device</h2>
                </div>

                <div class="overflow-x-auto bg-white px-6 py-10 rounded-lg shadow-lg">
                    <div class="w-full flex justify-end mb-4">
                        <a href="{{ route('device.create') }}" class="text-white px-4 py-2 rounded-lg shadow-lg hover:scale-110 bg-blue-400">
                            Add New
                        </a>
                    </div>
                    <table class="min-w-full border border-gray-300 rounded-lg shadow-md">
                        <thead class="bg-gray-200 text-gray-600 uppercase text-sm leading-normal">
                            <tr class="text-[12px]">
                                <th class="py-3 px-6 text-left">No</th>
                                <th class="py-3 px-6 text-center">Name</th>
                                <th class="py-3 px-6 text-left">Serial Number</th>
                                <th class="py-3 px-6 text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-700 text-sm font-light">
                            <tr class="border-b border-gray-200 hover:bg-gray-100">
                                <td class="py-3 px-6 text-left">1</td>
                                <td class="py-3 px-6 text-left flex gap-4 items-start">
                                    HK301-DTU-HPI2403H0120239
                                </td>
                                <td class="py-3 px-6 text-left">
                                    <span class=" text-[16px]">SN:KUYTJ21PUZ5H32KL</span>
                                    <br>
                                    <span>ID：279496</span>
                                </td>
                                <td class="py-3 px-6 text-center flex items-center justify-center gap-2">
                                    <a href="{{ route('device.show', ['id' => 123]) }}" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600 transition duration-200">Detail</a>
                                    <a href="{{ route('device.edit', ['id' => 123]) }}" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 transition duration-200">Edit</a>
                                    <button class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600 transition duration-200">Delete</button>
                                </td>
                            </tr>
                            <tr class="border-b border-gray-200 hover:bg-gray-100">
                                <td class="py-3 px-6 text-left">2</td>
                                <td class="py-3 px-6 text-left flex gap-4 items-start">
                                    HK301-DTU-HPI2403H0120239
                                </td>
                                <td class="py-3 px-6 text-left">
                                    <span class=" text-[16px]">SN:KUYTJ21PUZ5H32KL</span>
                                    <br>
                                    <span>ID：279496</span>
                                </td>
                                <td class="py-3 px-6 text-center flex items-center justify-center gap-2">
                                    <a href="{{ route('device.show', ['id' => 123]) }}" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600 transition duration-200">Detail</a>
                                    <a href="{{ route('device.edit', ['id' => 123]) }}" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 transition duration-200">Edit</a>
                                    <button class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600 transition duration-200">Delete</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
@endsection
