@props(['disabled' => false])

<input {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'border-zinc-200 focus:border-light-blue focus:ring-light-blue rounded-md shadow-sm']) !!}>
