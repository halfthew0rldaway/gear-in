@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'border-gray-300 bg-white text-gray-900 focus:border-gray-900 focus:ring-gray-900 rounded-2xl shadow-sm']) }}>
