@props([
    'tag' => '',
    'href' => '#',
])

@if($tag == 'a')
    <a {{ $attributes->merge([
        'class' => 'inline-flex items-center justify-center w-full sm:w-auto px-2 py-1 sm:px-3 sm:py-1
        bg-red-600 border border-transparent rounded-md text-5px sm:text-sm md:text-sm text-white
        tracking-widest hover:bg-red-500 active:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500
        focus:ring-offset-2 transition ease-in-out duration-150'
    ]) }} href="{{ $href }}">
        {{ $slot }}
    </a>
@else
    <button {{ $attributes->merge([
        'type' => 'submit',
        'class' => 'inline-flex items-center justify-center w-full sm:w-auto px-2 py-1 sm:px-3 sm:py-1 bg-red-600
        border border-transparent rounded-md text-5px sm:text-sm md:text-sm text-white tracking-widest
        hover:bg-red-500 active:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500
        focus:ring-offset-2 transition ease-in-out duration-150'
    ]) }}>
        {{ $slot }}
    </button>
@endif
