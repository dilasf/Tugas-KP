<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'E-Rapor') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="//unpkg.com/alpinejs" defer></script>

    <!-- Tailwind CSS -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body x-data="{ sidebarOpen: false }">
    <div class="min-h-screen bg-gray-100 dark:bg-gray-900 grid grid-cols-12 gap-1">

        <!-- Sidebar -->
        <div id="application-sidebar-dark"
            x-show="sidebarOpen"
            class="transition-all duration-300 transform lg:block col-span-3 bg-custom-dark border-e border-gray-800 pt-7 pb-10">
            @include('layouts.sidebar')
        </div>

        <!-- Main Content -->
        <div x-bind:class="sidebarOpen ? 'col-span-9' : 'col-span-12'" class="transition-all duration-300 overflow-x-auto">

            <!-- ========== HEADER ========== -->
            <header class="flex flex-wrap sm:justify-start sm:flex-nowrap z-50 w-full bg-white border-b border-gray-200 text-sm py-3 sm:py-0">
                <nav class="relative max-w-[85rem] flex flex-wrap basis-full items-center w-full mx-auto px-4 sm:flex sm:items-center sm:justify-between sm:px-6 lg:px-8" aria-label="Global">
                    <div class="flex items-center justify-between w-full">
                        <div class="flex items-center">

                            <!-- Sidebar Toggle -->
                            <div class="sticky top-0 inset-x-0 z-20 bg-white border-y">
                                <div class="flex items-center py-4">
                                    <button @click="sidebarOpen = !sidebarOpen" type="button" class="text-gray-500 hover:text-gray-600" aria-label="Toggle navigation">
                                        <span class="sr-only">Toggle Navigation</span>
                                        <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M4 6h12V5H4v1zm0 4h12V9H4v1zm0 4h12v-1H4v1z" clip-rule="evenodd" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                            <!-- End Sidebar Toggle -->

                            <!-- Title -->
                            <a class="h-9 w-auto fill-current text-gray-800 font-bold flex flex-col gap-y-4 gap-x-0 mt-5 sm:flex-row sm:items-center sm:justify-end sm:gap-y-0 sm:gap-x-7 sm:mt-0 sm:ps-7 ms-4" href="{{ route('dashboard-admin') }}">
                                E-RAPOR SDN DAWUAN
                            </a>
                        </div>

                        {{-- <!-- Log Out -->
                        <div class="flex flex-col gap-y-4 gap-x-0 mt-5 sm:flex-row sm:items-center sm:justify-end sm:gap-y-0 sm:gap-x-7 sm:mt-0 sm:ps-7 ms-4">
                            <!-- Authentication -->
                            <form class="px-6 sm:py-4 sm:px-0" method="POST" action="{{ route('logout') }}">
                                @csrf
                                <x-nav-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">
                                    {{ __('Log Out') }}
                                </x-nav-link>
                            </form>
                        </div>
                    </div> --}}
                </nav>
            </header>
            <!-- ========== END HEADER ========== -->

             <!-- Page Heading -->
             @if (isset($header))
             <header class="dark:bg-gray-800">
                 <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                     {{ $header }}
                 </div>
             </header>
         @endif

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>

             <!-- Notifikasi -->
            <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
            <script>
                const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                })
                @if(Session::has('message'))
                var type = "{{Session::get('alert-type')}}";
                switch (type) {
                case 'info':
                Toast.fire({
                icon: 'info',
                title: "{{ Session::get('message') }}"
                })
                break;
                case 'success':
                Toast.fire({
                icon: 'success',
                title: "{{ Session::get('message') }}"
                })
                break;
                case 'warning':
                Toast.fire({
                icon: 'warning',
                title: "{{ Session::get('message') }}"
                })
                break;
                case 'error':
                Toast.fire({
                icon: 'error',
                title: "{{ Session::get('message') }}"
                })
                break;
                case 'dialog_error':
                Swal.fire({
                icon: 'error',
                title: "Ooops",
                text: "{{ Session::get('message') }}",
                timer: 3000
                })
                break;
                }
                @endif
                @if ($errors->any())
                @php $list = null; @endphp
                @foreach($errors->all() as $error)
                @php $list .= '<li>'.$error.'</li>'; @endphp
                @endforeach
                Swal.fire({
                type: 'error',
                title: "Ooops",
                html: "<ul>{!! $list !!}</ul>",
                })
                @endif
            </script>
        </div>
    </div>
</body>
</html>
