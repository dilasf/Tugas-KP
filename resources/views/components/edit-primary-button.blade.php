@props([
    'tag' => '',
    'href' => '#',
])

@if($tag == 'a')
    <a {{ $attributes->merge([
        'class' => 'inline-flex items-center px-3 py-1 sm:px-3 sm:py-1 bg-light-yellow
        border border-transparent rounded-md text-xs sm:text-xs md:text-sm text-black
        tracking-widest hover:bg-amber-400 focus:bg-amber-400 active:bg-amber-400
        focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:ring-offset-2
        transition ease-in-out duration-150'
        ]) }} href="{{ $href }}">
       {{ $slot }}
    </a>
@else
    <button {{ $attributes->merge([
        'type' => 'submit',
        'class' => 'inline-flex items-center px-3 py-1 sm:px-3 sm:py-1 bg-light-yellow
        border border-transparent rounded-md text-xs sm:text-xs md:text-sm text-black
        tracking-widest hover:bg-amber-400 focus:bg-amber-400 active:bg-amber-400 focus:outline-none
        focus:ring-2 focus:ring-yellow-400 focus:ring-offset-2 transition ease-in-out duration-150'
        ]) }}>
        {{ $slot }}
    </button>
@endif
