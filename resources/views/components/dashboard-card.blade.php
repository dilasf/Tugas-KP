@props([
    'color' => 'blue',
    'route' => '',
    'count' => '',
    'title' => '',
    'dataModalTrigger' => false,
    'modalTarget' => '',
])

@php
    $colorClass = match ($color) {
        'purple' => 'bg-purple-500 dark:bg-purple-600',
        'aqua' => 'bg-teal-500 dark:bg-teal-600',
        'blue' => 'bg-blue-500 dark:bg-blue-600',
        'green' => 'bg-green-500 dark:bg-green-600',
        'orange' => 'bg-orange-500 dark:bg-orange-600',
        'red' => 'bg-red-500 dark:bg-red-600',
        'yellow' => 'bg-yellow-500 dark:bg-yellow-600',
        default => 'bg-gray-500 dark:bg-gray-600',
    };

    $gradient = match ($color) {
        'purple' => 'bg-gradient-to-r from-purple-500 to-purple-700',
        'aqua' => 'bg-gradient-to-r from-teal-500 to-teal-700',
        'blue' => 'bg-gradient-to-r from-blue-500 to-blue-700',
        'green' => 'bg-gradient-to-r from-green-500 to-green-700',
        'orange' => 'bg-gradient-to-r from-orange-500 to-orange-700',
        'red' => 'bg-gradient-to-r from-red-500 to-red-700',
        'yellow' => 'bg-gradient-to-r from-yellow-500 to-yellow-700',
        default => 'bg-gradient-to-r from-gray-500 to-gray-700',
    };

    $icons = [
        'Data Siswa' => 'img/icons8-student-center-96.png',
        'Data Guru' => 'img/icons8-teacher-64.png',
        'Akun' => 'img/icons8-account-96.png',
        'Mata Pelajaran' => 'img/icons8-study-50.png',
        'Kelas' => 'img/icons8-class-96.png',
        'Data Semester & TA' => 'img/icons8-date-span-64.png',
        'Data Nilai Sikap' => 'img/attitude.png',
        'Data Nilai Keterampilan' => 'img/skill.png',
        'Data Nilai Pengetahuan' => 'img/knowledge.png',
        'Rapor Menunggu Validasi' => 'img/report.png',
    ];

    $iconPath = $icons[$title] ?? 'img/default-icon.png';
@endphp

<div class="{{ $gradient }} rounded-lg shadow-md p-6 flex items-center space-x-4 border border-gray-200 dark:border-gray-700 transform transition-transform duration-300 hover:scale-105 hover:shadow-xl">
    <div class="flex-shrink-0">
        <img src="{{ asset($iconPath) }}" alt="{{ $title }} icon" class="w-12 h-12">
    </div>

    <div class="text-white">
        <h3 class="text-xl font-semibold mb-1">{{ $title }}</h3>
        <p class="text-sm">{{ $count }}</p>

        @if($dataModalTrigger && $modalTarget)
            <x-button-link :color="$color" :modalTrigger="true" :modalTarget="$modalTarget">
                Lihat Detail
            </x-button-link>
        @elseif(Route::has($route))
            <x-button-link :href="route($route)" :color="$color">
                Lihat Detail
            </x-button-link>
        @else
            <p class="text-red-500">Invalid route: {{ $route }}</p>
        @endif
    </div>
</div>
