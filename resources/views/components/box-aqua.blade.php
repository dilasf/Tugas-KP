@props([
    'tag' => 'button',
    'href' => '#',
    'count' => '',
    'title' => ''
])

<div class="bg-aqua-blue text-white p-5 mb-0" style="width: 259px; height: 110px;">
    <div class="items-center">
        <div class="text-3xl font-semibold px-1">{{ $count }}</div>
        <div class="text-lg font-medium py-3 px-1">{{ $title }}</div>
    </div>
</div>

@if($tag == 'a')
    <a {{ $attributes->merge([
        'class' => 'inline-flex items-center justify-center w-full h-7
        px-2 py-1 sm:px-3 sm:py-2 md:px-4 md:py-2 bg-semi-dark-aqua border
        border-transparent font-semibold text-xs sm:text-xs md:text-sm
        text-white tracking-widest hover:bg-semi-dark-aqua focus:bg-semi-dark-aqua
        active:bg-aqua-blue focus:outline-none transition ease-in-out duration-150'
    ]) }} href="{{ $href }}">
    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
        <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="2"></circle>
        <path d="M8 12h8m-4-4l4 4-4 4" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
    </svg>

    </a>
@else
    <button {{ $attributes->merge([
        'type' => 'submit',
        'class' => 'inline-flex items-center justify-center w-full h-7 px-2 py-1
        sm:px-3 sm:py-2 md:px-4 md:py-2 bg-semi-dark-aqua border border-transparent
        font-semibold text-xs sm:text-xs md:text-sm text-white tracking-widest
        hover:bg-aqua-blue focus:bg-semi-dark-aqua active:bg-semi-dark-aqua focus:outline-none
       transition ease-in-out duration-150'
    ]) }} onclick="window.location.href='{{ $href }}'">
    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
        <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="2"></circle>
        <path d="M8 12h8m-4-4l4 4-4 4" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
    </svg>
    </button>
@endif
