@extends('layouts.app')

@section('content')
    <div x-data="{ sidebar: true, popupNavbar: false }" class="relative flex">
        <div class="absolute w-full h-[250px] bg-[#083C76] -z-10">

        </div>
        @include('components.sidebar')
        <div class="min-h-screen" :class="sidebar ? 'w-10/12' : 'w-full'">
            @include('components.navbar')
            <div class="container mx-auto p-4 max-h-screen overflow-auto">
                @include('components.breadcrumb', [
                    'lists' => [
                        [
                            'title' => 'Devices',
                            'route' => '#',
                            'is_active' => true,
                        ],
                    ],
                ])
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-2xl font-semibold text-gray-800 mb-4 text-white">Device List</h2>
                </div>

                <div class="overflow-x-auto bg-white px-6 py-10 rounded-lg shadow-lg">
                    <div class="my-4">
                        <form action="" method="GET" id="groupForm">
                            <select name="group_id" id="group_id" class="w-full px-4 py-2 border rounded-lg" onchange="document.getElementById('groupForm').submit();">
                                <option value="">All Group</option>
                                @foreach ($groups as $group)
                                    <option @if($group['group_id'] == $group_id) selected @endif value="{{ $group['group_id'] }}">
                                        {{ $group['group_name'] }}
                                    </option>
                                @endforeach
                            </select>
                        </form>                        
                    </div>
                    <table id="data-table" class="min-w-full border border-gray-300 rounded-lg shadow-md">
                        <thead class="bg-gray-200 text-gray-600 uppercase text-sm leading-normal">
                            <tr class="text-[12px]">
                                <th class="py-3 px-6 text-left">No</th>
                                <th class="py-3 px-6 text-center">Name</th>
                                <th class="py-3 px-6 text-left">Serial Number</th>
                                <th class="py-3 px-6 text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody id="data-body" class="text-gray-700 text-sm font-light">
                            <!-- Data akan di-render di sini -->
                        </tbody>
                    </table>
                    <!-- Paginasi -->
                    <div id="pagination" class="flex justify-center mt-4 gap-2">
                        <!-- Tombol paginasi akan di-render di sini -->
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Data dari Laravel
        const data = @json($devices);
    
        const rowsPerPage = 10; // Jumlah data per halaman
        let currentPage = 1;
    
        function renderTable(data, page = 1) {
            const start = (page - 1) * rowsPerPage;
            const end = page * rowsPerPage;
            const paginatedData = data.slice(start, end);
    
            const tableBody = document.getElementById("data-body");
            tableBody.innerHTML = "";
    
            paginatedData.forEach((item, index) => {
                const row = `
                    <tr class="border-b border-gray-200 hover:bg-gray-100 ${item.is_line == 0 ? 'text-gray-300' : 'text-black'}">
                        <td class="py-3 px-6 text-left">${start + index + 1}</td>
                        <td class="py-3 px-6 text-left flex gap-1 items-center">
                            <span class="w-[10px] h-[10px] ${item.is_line == 1 ? 'bg-green-500' : 'bg-red-500'} rounded-full"></span>${item.device_name ?? '-'}
                        </td>
                        <td class="py-3 px-6 text-left">
                            <span class="text-[16px]">SN: ${item.device_no ?? '-'}</span>
                            <br>
                            <span>ID: ${item.id ?? '-'}</span>
                        </td>
                        <td class="py-3 px-6 text-center flex items-center justify-center gap-2">
                            <a href="/devices/${item.id ?? ''}/edit" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-opacity-90 transition duration-200">Edit</a>
                            <a href="/devices/${item.id ?? ''}" class="bg-[#083C76] text-white px-4 py-2 rounded hover:bg-opacity-90 transition duration-200">Sensor List</a>
                        </td>
                    </tr>
                `;
                tableBody.innerHTML += row;
            });
        }
    
        function renderPagination(totalRows) {
            const totalPages = Math.ceil(totalRows / rowsPerPage);
            const pagination = document.getElementById("pagination");
            pagination.innerHTML = "";
    
            // Tombol Previous
            const prevButton = document.createElement("button");
            prevButton.textContent = "Previous";
            prevButton.className =
                "px-4 py-2 rounded bg-gray-300 hover:bg-gray-400 transition duration-200" +
                (currentPage === 1 ? " opacity-50 cursor-not-allowed" : "");
            prevButton.disabled = currentPage === 1;
            prevButton.addEventListener("click", () => {
                if (currentPage > 1) {
                    currentPage--;
                    renderTable(data, currentPage);
                    renderPagination(totalRows);
                }
            });
            pagination.appendChild(prevButton);
    
            // Tombol angka dengan batas maksimal 3
            const maxButtons = 3;
            const startPage = Math.max(1, currentPage - Math.floor(maxButtons / 2));
            const endPage = Math.min(totalPages, startPage + maxButtons - 1);
    
            for (let i = startPage; i <= endPage; i++) {
                const button = document.createElement("button");
                button.textContent = i;
                button.className =
                    "px-4 py-2 rounded bg-gray-300 hover:bg-gray-400 transition duration-200" +
                    (i === currentPage ? " bg-blue-500 text-white" : "");
                button.addEventListener("click", () => {
                    currentPage = i;
                    renderTable(data, currentPage);
                    renderPagination(totalRows);
                });
                pagination.appendChild(button);
            }
    
            // Tombol Next
            const nextButton = document.createElement("button");
            nextButton.textContent = "Next";
            nextButton.className =
                "px-4 py-2 rounded bg-gray-300 hover:bg-gray-400 transition duration-200" +
                (currentPage === totalPages ? " opacity-50 cursor-not-allowed" : "");
            nextButton.disabled = currentPage === totalPages;
            nextButton.addEventListener("click", () => {
                if (currentPage < totalPages) {
                    currentPage++;
                    renderTable(data, currentPage);
                    renderPagination(totalRows);
                }
            });
            pagination.appendChild(nextButton);
        }
    
        // Render pertama kali
        renderTable(data, currentPage);
        renderPagination(data.length);
    </script>
    
@endsection
