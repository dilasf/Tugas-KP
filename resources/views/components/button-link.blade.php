@props([
    'href' => '#',
    'color' => 'blue',
    'modalTrigger' => false, // Menentukan apakah tombol memicu modal
    'modalTarget' => '', // Target modal jika tombol memicu modal
])

<a
    @if($modalTrigger)
        href="javascript:void(0);"
        data-hs-overlay="{{ $modalTarget }}"
    @else
        href="{{ $href }}"
    @endif
    class="inline-flex items-center justify-center px-4 py-2 text-sm font-semibold text-white bg-{{ $color }}-600 rounded-lg shadow-sm hover:bg-{{ $color }}-600 hover:underline focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-{{ $color }}-500 dark:bg-{{ $color }}-700 dark:hover:bg-{{ $color }}-600 dark:focus:ring-{{ $color }}-400 transition duration-150"
>
    {{ $slot }}
</a>
