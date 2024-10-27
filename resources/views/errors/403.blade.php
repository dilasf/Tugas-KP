<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>403 Forbidden</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 flex items-center justify-center h-screen">

    <div class="bg-white rounded-lg shadow-md mx-4 overflow-hidden w-full max-w-lg p-8">
        <div class="mb-4 flex justify-center items-center">
            <!-- Icon -->
            <span class="flex-shrink-0 inline-flex justify-center items-center w-16 h-16 sm:w-20 sm:h-20 rounded-full border-4 border-red-50 bg-red-100 text-red-500 dark:bg-red-700 dark:border-red-600 dark:text-red-100 mr-6">
                <svg class="flex-shrink-0 w-10 h-10" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 16 16">
                    <path d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"/>
                </svg>
            </span>
            <!-- End Icon -->
        </div>
        <h1 class="text-2xl font-bold text-red-600 mb-4 text-center">Akses Ditolak</h1>
        <p class="text-gray-700 mb-6 text-center">Maaf, Anda tidak memiliki izin untuk mengakses halaman ini</p>
        <a class="h-9 w-auto fill-current text-gray-800 dark:text-gray-200 font-bold flex items-center gap-2 mt-5 sm:mt-0" href="{{ route('dashboard') }}">
            <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
            Kembali
        </a>
    </div>

</body>
</html>
