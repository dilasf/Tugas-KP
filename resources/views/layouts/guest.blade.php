<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'E-Rapor') }}</title>

        <link rel="icon" href="{{ asset('img/logopendidik.png') }}" type="image/png">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased">

        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100 dark:bg-gray-900">
            <div class="text-center mt-8">
                <a href="/">
                    <img src="{{ asset('img/logopendidik.png') }}" class="w-32 h-32 mx-auto" alt="Logo">
                </a>
                <h1 class="text-2xl font-extrabold text-gray-700 dark:text-gray-100 mt-4">
                    LAPORAN KEMAJUAN SISWA
                </h1>
                <h2 class="text-xl font-semibold text-gray-600 dark:text-gray-300">
                    SEKOLAH DASAR NEGERI DAWUAN
                </h2>
            </div>

            <div class="flex items-stretch justify-center w-full mt-6">
                <div class="w-full sm:max-w-xl lg:max-w-3xl bg-white dark:bg-gray-800 shadow-md overflow-hidden sm:rounded-lg flex"> <!-- Mengatur lebar kotak putih -->
                    <img src="{{ asset('img/ilustrasi.png') }}" alt="Ilustrasi" class="h-auto w-1/2 object-cover" /> <!-- Gambar di sisi kiri -->
                    <div class="w-1/2 p-4 flex flex-col justify-center"> <!-- Konten Form di sisi kanan -->
                        {{ $slot }}
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>


