@props(['active'])

@php
$classes = ($active ?? false)
            ? 'w-full flex items-center gap-x-3 py-2 px-2.5 text-sm text-white hover:text-white-300 focus:outline-none focus:ring-1 focus:ring-gray-600 hover:cursor-default'
            : 'w-full flex items-center gap-x-3 py-2 px-2.5 text-sm text-white hover:bg-hover-side hover:text-white-300 focus:outline-none';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
