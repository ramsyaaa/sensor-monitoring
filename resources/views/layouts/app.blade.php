<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="//unpkg.com/alpinejs" defer></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://fonts.googleapis.com/css2?family=Ubuntu:wght@400;700&family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <link rel="stylesheet" href="{{ asset('asset/css/style.css') }}">
    <title>Sensor Monitoring</title>

    <style>
        body {
            font-family: 'Ubuntu', 'Roboto', 'Helvetica Neue', sans-serif;
        }

        #map {
            height: 400px;
        }

        .hide-scrollbar {
            overflow: auto; /* Memungkinkan scroll di dalam elemen */
            scrollbar-width: none; /* Untuk Firefox */
            -ms-overflow-style: none; /* Untuk Internet Explorer dan Edge */
        }

        .hide-scrollbar::-webkit-scrollbar {
            display: none; /* Menyembunyikan scrollbar untuk Chrome, Safari, dan Opera */
        }

        .canvasjs-chart-credit {
            display: none !important;
        }
    </style>
</head>
<body class="bg-gray-200">
    @yield('content')

    @if (session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Success',
                text: '{{ session('success') }}',
            });
        </script>
    @elseif (session('error'))
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: '{{ session('error') }}',
            });
        </script>
    @endif

    <script>
        function confirmDelete(url, idElement) {
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Data ini akan dihapus secara permanen!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Set the form action to the URL and submit the form
                    const form = document.getElementById(idElement);
                    form.action = url;
                    form.submit();
                }
            });
        }

        function refreshToken() {
            fetch('{{ route("auth.refresh-token") }}', {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json'
            }
            });
        }

        setInterval(refreshToken, 300000);
    </script>
</body>
</html>
