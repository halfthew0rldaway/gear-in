@props(['status'])

@php
    $map = [
        'pending' => 'bg-amber-50 text-amber-700 border border-amber-200/60',
        'paid' => 'bg-sky-50 text-sky-700 border border-sky-200/60',
        'shipped' => 'bg-indigo-50 text-indigo-700 border border-indigo-200/60',
        'completed' => 'bg-emerald-50 text-emerald-700 border border-emerald-200/60',
        'cancelled' => 'bg-rose-50 text-rose-700 border border-rose-200/60',
    ];

    $label = [
        'pending' => 'Menunggu',
        'paid' => 'Dibayar',
        'shipped' => 'Dikirim',
        'completed' => 'Selesai',
        'cancelled' => 'Dibatalkan',
    ][$status] ?? ucfirst($status);

    $badgeClass = $map[$status] ?? 'bg-gray-50 text-gray-600 border border-gray-200';
@endphp

<span {{ $attributes->merge(['class' => 'px-3 py-1 rounded-full text-xs uppercase tracking-[0.3em] ' . $badgeClass]) }}>
    {{ $label }}
</span>