@props([
'tag' => '',
'href' => '#',
])
@if($tag == 'a')
<a {{ $attributes->merge(['class' => 'inline-flex items-
    center px-4 py-2 bg-light-gray dark:bg-gray-800 border border-
    gray-300 dark:border-gray-500 rounded-md font-semibold text-
    xs text-slate-600 dark:text-gray-300 tracking-widest
    shadow-sm hover:bg-slate-400 dark:hover:bg-gray-700
    focus:outline-none focus:ring-2 focus:ring-gray-200
    focus:ring-offset-2 dark:focus:ring-offset-gray-800
    disabled:opacity-25 transition ease-in-out duration-150']) }}
    href="{{ $href }}">
    {{ $slot }}
</a>
@else
<button {{ $attributes->merge(['type' => 'button',
    'class' => 'inline-flex items-center px-4 py-2 bg-light-gray
    dark:bg-gray-800 border border-gray-300 dark:border-gray-500
    rounded-md font-semibold text-xs text-slate-600 dark:text-
    gray-300 uppercase tracking-widest shadow-sm hover:bg-slate-400
    dark:hover:bg-gray-700 focus:outline-none focus:ring-2
    focus:ring-gray-200 focus:ring-offset-2 dark:focus:ring-offset-gray-800 disabled:opacity-25 transition ease-in-out
    duration-150']) }}>
    {{ $slot }}
</button>
@endif
