@props([
    'tag' => '',
    'href' => '#',
])

@if($tag == 'a')
    <a {{ $attributes->merge([
        'class' => 'inline-flex items-center px-3 py-1 sm:px-3 sm:py-1 bg-light-green
        border border-transparent rounded-md text-xs sm:text-xs md:text-sm text-white
        tracking-widest hover:bg-green-600 focus:bg-green-600 active:bg-green-600
        focus:outline-none focus:ring-2 focus:ring-green-400 focus:ring-offset-2
        transition ease-in-out duration-150'
        ]) }} href="{{ $href }}">
       {{ $slot }}
    </a>
@else
    <button {{ $attributes->merge([
        'type' => 'submit',
        'class' => 'inline-flex items-center px-3 py-1 sm:px-3 sm:py-1 bg-light-green
        border border-transparent rounded-md text-xs sm:text-xs md:text-sm text-white
        tracking-widest hover:bg-green-600 focus:bg-green-600 active:bg-green-600
        focus:outline-none focus:ring-2 focus:ring-green-400 focus:ring-offset-2
        transition ease-in-out duration-150'
        ]) }}>
       {{ $slot }}
    </button>
@endif
