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
                                'title' => 'Reports',
                                'route' => '#',
                                'is_active' => true
                            ]
                        ]
                    ])
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-2xl font-semibold text-gray-800 mb-4 text-white">Report List</h2>
                    </div>
                </div>
                <div>
                    <div class="flex items-center">
                        <div onclick="openTab(this, 'ListDevice')" class="tab flex px-4 py-2 rounded-t-lg cursor-pointer text-[#083C76] bg-white">
                            Devices
                        </div>
                        <div onclick="openTab(this, 'ListDownload')" class="tab flex px-4 py-2 rounded-t-lg cursor-pointer text-white">
                            List
                        </div>
                    </div>
                    <div>
                        <div>
                            <div id="ListDevice" class="overflow-x-auto bg-white px-6 pb-10 rounded-b-lg shadow-lg">
                                <div class="flex flex-col gap-2 mt-4 items-start">
                                    <div class="py-5 font-bold text-[20px]">
                                        Report List
                                    </div>
                                </div>
                                <table class="min-w-full border border-gray-300 rounded-lg shadow-md">
                                    <thead class="bg-gray-200 text-gray-600 uppercase text-sm leading-normal">
                                        <tr class="text-[12px]">
                                            <th class="py-3 px-6 text-left">No</th>
                                            <th class="py-3 px-6 text-left">Device</th>
                                            <th class="py-3 px-6 text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody class="text-gray-700 text-sm font-light">
                                        @foreach ($device as $index => $item)
                                        @foreach ($item as $index1 => $item1)
                                        <tr>
                                            <td class="text-[18px] font-bold py-4 pl-3">
                                                {{ $index1 }}
                                            </td>
                                        </tr>
                                        @foreach ($item1 as $index2 => $item2)
                                        <tr class="border-b border-gray-200 hover:bg-gray-100">
                                            <td class="py-3 px-6 text-left">{{ $index2 + 1 }}</td>
                                            <td class="py-3 px-6 flex gap-2 items-center">
                                                <div class="w-[10px] h-[10px] rounded-full">
                                                </div>
                                                <div class="text-left flex gap-4 items-start">
                                                    <div>
                                                        <img class="max-w-[40px]" src="{{ checkUrlIcon($item2['device_name']) }}" alt="">
                                                    </div>
                                                    <div>
                                                        <span class="font-bold text-[16px]">{{ $item2['device_name'] }}</span>
                                                        <br>
                                                        <span class="text-[12px]">Point code : {{ $item2['point_code'] }}</span>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="py-3 px-6">
                                                <div class="flex gap-2 justify-center">
                                                    <a title="Realtime Curv" href="javascript:void(0)" onclick="createReport({{ $item2['id'] }}, '{{ $item2['device_name'] }}', '{{ $item2['point_code'] }}')" 
                                                        class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600 transition duration-200 flex items-center gap-2">
                                                        Create
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                        @endforeach
                                        @endforeach
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div id="ListDownload" class="hidden">
                            {{--  --}}
                        </div>
                    </div>
                </div>
            </div>

            <div id="detail-panel" style="z-index: 1000" class="fixed top-0 right-0 w-full md:w-2/3 h-screen bg-white shadow-lg p-4 invisible-panel overflow-y-auto">
                <button onclick="closePanel()" class="absolute top-2 right-4 text-gray-600 text-[24px]">&times;</button>
                <div id="detail-content"></div>
            </div>

        </div>
    </div>

    <script>
        let downloadList = false;
        function createReport(id, device_name, point_code) {
            // Tampilkan panel
            document.getElementById('detail-panel').classList.remove('invisible-panel');

            // Kirim request untuk mendapatkan data dari API dan tambahkan `from_map=true` dalam parameter
            fetch(`/reports/${id}/create?device_name=${device_name}&point_code=${point_code}`, {
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
    
        function submitData(button, id){
            $(button).prop('disabled', true).removeClass('bg-indigo-600 hover:bg-indigo-700').addClass('bg-gray-500');

            const formData = {
                start_date: $("#start_date").val(),
                end_date: $("#end_date").val(),
                _token: '{{ csrf_token() }}'
            };
    
            $.ajax({
                url: `/reports/${id}`,
                method: 'POST',
                data: formData,
                success: function (response) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: response.message,
                    }).then(() => {
                        $(button).prop('disabled', false).removeClass('bg-gray-500').addClass('bg-indigo-600 hover:bg-indigo-700');
                        closePanel();
                        getDownloadList();
                    });
                },
                error: function (xhr) {
                    if (xhr.status === 422) {
                        const errors = xhr.responseJSON.errors;
                        $('#start_date_error').text(errors.start_date ?? '');
                        $('#end_date_error').text(errors.end_date ?? '');
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Something went wrong!',
                        });
                    }
                }
            });
        }

        function openTab(tab, id){
            $(".tab").removeClass("text-[#083C76] bg-white");
            $(".tab").addClass("text-white");

            $(tab).addClass('text-[#083C76] bg-white');
            $(tab).removeClass('text-white');

            if(id == 'ListDevice'){
                $("#ListDevice").removeClass("hidden");
                $("#ListDownload").addClass("hidden");
            }else{
                if (downloadList === false) {
                    getDownloadList();
                }

                $("#ListDevice").addClass("hidden");
                $("#ListDownload").removeClass("hidden");
            }
        }

        async function getDownloadList() {
            try {
                downloadList = true;
                // Call the API
                const response = await fetch('/reports/list'); // Replace with your API endpoint
                if (!response.ok) {
                    throw new Error('Failed to fetch the download list.');
                }

                // Parse the JSON response
                let data = await response.json();
                data = data['data'];

                // Get the target element and clear its contents
                const listDownload = document.getElementById('ListDownload');
                listDownload.innerHTML = '';

                // Create the table structure
                const tableHtml = `
                    <div class="overflow-x-auto bg-white px-6 pb-10 rounded-b-lg shadow-lg">
                        <div class="flex flex-col gap-2 mt-4 items-start">
                            <div class="py-5 font-bold text-[20px]">
                                Report List
                            </div>
                        </div>
                        <table class="min-w-full border border-gray-300 rounded-lg shadow-md">
                            <thead class="bg-gray-200 text-gray-600 uppercase text-sm leading-normal">
                                <tr class="text-[12px]">
                                    <th class="py-3 px-6 text-left">No</th>
                                    <th class="py-3 px-6 text-left">Device</th>
                                    <th class="py-3 px-6 text-left">Generated At</th>
                                    <th class="py-3 px-6 text-left">Start Date</th>
                                    <th class="py-3 px-6 text-left">End Date</th>
                                    <th class="py-3 px-6 text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody class="text-gray-700 text-sm font-light">
                                ${data.map((item, index) => `
                                    <tr class="border-b border-gray-200 hover:bg-gray-100">
                                        <td class="py-3 px-6 text-left">${index + 1}</td>
                                        <td class="py-3 px-6">
                                            ${item.device_name} (${item.point_code})
                                        </td>
                                        <td class="py-3 px-6">
                                            ${formatDate(item.generated_at, true)}
                                        </td>
                                        <td class="py-3 px-6">
                                            ${formatDate(item.start_date, false)}
                                        </td>
                                        <td class="py-3 px-6">
                                            ${formatDate(item.end_date, false)}
                                        </td>
                                        <td class="py-3 px-6">
                                            <div class="flex gap-2 justify-center">
                                                ${item.status !== 'pending' ? `
                                                    <a title="Download" href="javascript:void(0)" onclick="downloadReport(${item.id})"
                                                        class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600 transition duration-200 flex items-center gap-2">
                                                        Download
                                                    </a>
                                                ` : ''}
                                            </div>
                                        </td>
                                    </tr>
                                `).join('')}
                            </tbody>
                        </table>
                    </div>
                `;


                // Append the table to the target element
                listDownload.innerHTML = tableHtml;
            } catch (error) {
                console.error(error.message);
                alert('Failed to load the download list. Please try again.');
            } finally {
                // Reset downloadList flag if needed
                window.downloadList = false;
            }
        }

        async function downloadReport(id) {
            try {
                // Panggil API untuk mengunduh file
                const response = await fetch('/reports/download/' + id); // Ganti dengan endpoint API yang sesuai
                if (!response.ok) {
                    throw new Error('Failed to fetch the download API.');
                }

                // Ambil file sebagai Blob (misalnya file xlsx)
                const blob = await response.blob();

                // Cek apakah header Content-Disposition ada untuk mendapatkan nama file
                const contentDisposition = response.headers.get('Content-Disposition');
                let filename = 'report.xlsx';  // Nama default file jika tidak ada header Content-Disposition

                if (contentDisposition && contentDisposition.indexOf('attachment') !== -1) {
                    const matches = /filename="([^"]*)"/.exec(contentDisposition);
                    if (matches != null && matches[1]) {
                        filename = matches[1];  // Ambil nama file dari header
                    }
                }

                // Membuat URL objek dari Blob
                const url = window.URL.createObjectURL(blob);

                // Membuat elemen anchor untuk memicu unduhan
                const link = document.createElement('a');
                link.href = url;
                link.download = filename;  // Gunakan nama file yang sesuai

                // Simulasikan klik pada elemen anchor untuk mulai mengunduh
                document.body.appendChild(link);  // Menambahkan elemen anchor ke DOM
                link.click();                    // Klik elemen untuk mengunduh file

                // Hapus elemen anchor setelah unduhan
                document.body.removeChild(link);

                // Revoke URL objek setelah selesai
                window.URL.revokeObjectURL(url);

            } catch (error) {
                alert('Failed to load the download file. Please try again.');
            } finally {
                // Reset downloadList flag jika perlu
                window.downloadList = false;
            }
        }


        function formatDate(isoDate, includeTime = false) {
            const date = new Date(isoDate);
            const day = String(date.getDate()).padStart(2, '0');
            const month = String(date.getMonth() + 1).padStart(2, '0'); // Month is 0-indexed
            const year = date.getFullYear();

            // Format hanya tanggal jika includeTime false
            if (includeTime) {
                const hours = String(date.getHours()).padStart(2, '0');
                const minutes = String(date.getMinutes()).padStart(2, '0');
                const seconds = String(date.getSeconds()).padStart(2, '0');
                return `${day}-${month}-${year} ${hours}:${minutes}:${seconds}`;
            }

            // Format hanya tanggal
            return `${day}-${month}-${year}`;
        }


    </script>
@endsection
