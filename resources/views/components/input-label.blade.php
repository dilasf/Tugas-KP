@props(['value'])

<label {{ $attributes->merge(['class' => 'block font-medium text-sm text-black font-semibold']) }}>
    {{ $value ?? $slot }}
</label>
