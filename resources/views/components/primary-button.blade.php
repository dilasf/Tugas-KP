@props([
    'tag' => '',
    'href' => '#',
])

@if($tag == 'a')
    <a {{ $attributes->merge([
        'class' => 'inline-flex items-center px-2 py-1 sm:px-3 sm:py-2 md:px-4 md:py-2
        bg-light-blue dark:bg-gray-200 border border-transparent rounded-md font-semibold
        text-xs sm:text-xs md:text-sm text-white dark:text-gray-800 tracking-widest
        hover:bg-sky-600 dark:hover:bg-white focus:bg-sky-600 dark:focus:bg-white active:bg-sky-600
        dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-300 focus:ring-offset-2
        dark:focus:ring-offset-gray-800 transition ease-in-out duration-150'
        ]) }} href="{{ $href }}">
        {{ $slot }}
    </a>
@else
    <button {{ $attributes->merge([
        'type' => 'submit',
        'class' => 'inline-flex items-center px-2 py-1 sm:px-3 sm:py-2 md:px-4 md:py-2 bg-light-blue
        dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs sm:text-xs
        md:text-sm text-white dark:text-gray-800 tracking-widest hover:bg-sky-600 dark:hover:bg-white
        focus:bg-sky-600 dark:focus:bg-white active:bg-sky-600 dark:active:bg-gray-300 focus:outline-none
        focus:ring-2 focus:ring-blue-300 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition
        ease-in-out duration-150'
        ]) }}>
        {{ $slot }}
    </button>
@endif
