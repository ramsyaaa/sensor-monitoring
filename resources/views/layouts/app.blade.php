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
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />

    <link rel="stylesheet" href="{{ asset('asset/css/input.css') }}">
    <title>Sensor Monitoring - {{ $title ?? '' }}</title>

    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
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
    </style>
</head>
<body class="bg-gray-200">
    @yield('content')
</body>
</html>
