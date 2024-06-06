@props([
'tag' => '',
'href' => '#',
])
@if($tag == 'a')
<a {{ $attributes->merge(['class' => 'inline-flex items-
    center px-4 py-2 bg-light-gray border border-gray-300
    rounded-md font-semibold text-xs text-slate-600 tracking-widest
    shadow-sm hover:bg-slate-400 focus:outline-none focus:ring-2 focus:ring-gray-200
    focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150']) }}
    href="{{ $href }}">
    {{ $slot }}
</a>
@else
<button {{ $attributes->merge(['type' => 'button',
    'class' => 'inline-flex items-center px-4 py-2 bg-light-gray
    border border-gray-300 rounded-md font-semibold text-xs text-slate-600
    uppercase tracking-widest shadow-sm hover:bg-slate-400 focus:outline-none
    focus:ring-2 focus:ring-gray-200 focus:ring-offset-2 disabled:opacity-25
    transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
@endif
