@props(['active'])

@php
$classes = ($active ?? false)
            ? 'w-full flex items-center gap-x-3 py-2 px-2.5 bg-gray-700 text-sm text-white rounded-lg hover:bg-gray-800 hover:text-white-300 focus:outline-none focus:ring-1 focus:ring-gray-600 hover:cursor-default'
            : 'font-medium text-gray-500 hover:text-gray-400 px-6 sm:py-6 sm:px-0 dark:text-neutral-400 dark:hover:text-neutral-500';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
