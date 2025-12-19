@props(['messages'])

@if ($messages)
    <div {{ $attributes->merge(['class' => 'bg-red-50 border border-red-200 rounded-lg p-3 mt-2']) }}>
        <ul class="space-y-1">
            @foreach ((array) $messages as $message)
                <li class="flex items-start gap-2 text-sm text-red-700">
                    <svg class="w-4 h-4 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                            clip-rule="evenodd" />
                    </svg>
                    <span>{{ $message }}</span>
                </li>
            @endforeach
        </ul>
    </div>
@endif