@props(['value'])

<label {{ $attributes->merge(['class' => 'block font-medium text-xs uppercase tracking-[0.4em] text-gray-500']) }}>
    {{ $value ?? $slot }}
</label>
