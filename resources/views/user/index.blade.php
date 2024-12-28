@extends('layouts.app')

@section('content')
    <style>
        #detail-panel {
            transition: transform 0.3s ease;
        }
        .invisible-panel {
            transform: translateX(100%);
        }
    </style>
    <div x-data="{ sidebar: true, popupNavbar: false }" class="relative flex">
        <div class="absolute w-full h-[250px] bg-[#083C76] -z-10">

        </div>
        <div class="min-h-screen w-full">
            @include('components.navbar')
            <div class="mx-auto max-h-screen overflow-auto">
                <div class="px-4">
                    @include('components.breadcrumb', [
                        'lists' => [
                            [
                                'title' => 'Users',
                                'route' => '#',
                                'is_active' => true,
                            ],
                        ],
                    ])
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-2xl font-semibold text-gray-800 mb-4 text-white">User List</h2>
                    </div>
                </div>

                <div class="overflow-x-auto bg-white px-6 py-10 rounded-lg shadow-lg">
                    <div class="my-4">
                        <a href="{{ route('user.create') }}" class="px-4 py-2 bg-[#083C76] rounded-lg shadow-lg hhover:opacity-80 text-white mb-4">Create</a>
                    </div>
                    <table id="data-table" class="min-w-full border border-gray-300 rounded-lg shadow-md">
                        <thead class="bg-gray-200 text-gray-600 uppercase text-sm leading-normal">
                            <tr class="text-[12px]">
                                <th class="py-3 px-6 text-left">No</th>
                                <th class="py-3 px-6 text-center">Username</th>
                                <th class="py-3 px-6 text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody id="data-body" class="text-gray-700 text-sm font-light">
                            @foreach ($users as $user)
                            <tr class="border-b border-gray-200 hover:bg-gray-100 ${item.is_line === 0 ? 'text-gray-300' : 'text-black'}">
                                <td class="py-3 px-6 text-left">{{ $loop->iteration }}</td>
                                <td class="py-3 px-6 text-center">
                                    <span class="text-[16px] font-bold">{{ $user['username'] }}</span>
                                </td>
                                <td class="py-3 px-6 text-center flex items-center justify-center gap-2">
                                    <button title="Edit User" onclick="showDetail('{{ $user['id'] }}', '{{ $user['username'] }}')" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-opacity-90 transition duration-200">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <a title="Sensor List" href="#" onclick="confirmDelete('{{ route('user.destroy', ['id' => $user['id']]) }}', 'delete-form')" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-opacity-90 transition duration-200">
                                        <i class="fa fa-solid fa-trash"></i>
                                    </a>
                                    
                                    <form id="delete-form" method="POST" style="display: none;">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div id="detail-panel" style="z-index: 1000" class="fixed top-0 right-0 w-full md:w-2/3 h-screen bg-white shadow-lg p-4 invisible-panel overflow-y-auto">
        <button onclick="closePanel()" class="absolute top-2 right-4 text-gray-600 text-[24px]">&times;</button>
        <div id="detail-content"></div>
    </div>
    
    <script>
        function showDetail(id, username) {
            // Tampilkan panel
            document.getElementById('detail-panel').classList.remove('invisible-panel');

            // Kirim request untuk mendapatkan data dari API dan tambahkan `from_map=true` dalam parameter
            fetch(`/users/${id}/edit?username=${username}`, {
                method: 'GET',
            })
            .then(response => response.text())  // Mengambil sebagai teks HTML
            .then(data => {
                // Isi konten detail dengan data respons
                document.getElementById('detail-content').innerHTML = data;
            })
            .catch(error => console.error('Error:', error));
        }

        // Fungsi untuk menutup panel
        function closePanel() {
            document.getElementById('detail-panel').classList.add('invisible-panel');
            document.getElementById('detail-content').innerHTML = ''; // Kosongkan konten
        }
    </script>
@endsection
