@props(['status'])

@php
    $map = [
        'pending' => 'bg-gray-900 text-white',
        'paid' => 'bg-gray-800 text-white',
        'shipped' => 'bg-white border border-gray-900 text-gray-900',
        'completed' => 'bg-gray-200 text-gray-900',
        'cancelled' => 'bg-red-100 text-red-600 border border-red-200',
    ];

    $label = ucfirst($status);
    $badgeClass = $map[$status] ?? 'bg-gray-100 text-gray-600';
@endphp

<span {{ $attributes->merge(['class' => 'px-3 py-1 rounded-full text-xs uppercase tracking-[0.3em] '.$badgeClass]) }}>
    {{ $label }}
</span>

