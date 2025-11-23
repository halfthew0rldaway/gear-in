@props(['product'])

@php
    $image = $product->image_path;
    if ($image && ! \Illuminate\Support\Str::startsWith($image, ['http://', 'https://'])) {
        $image = \Illuminate\Support\Facades\Storage::url($image);
    }
@endphp

<div class="group bg-white border border-gray-200 rounded-3xl p-5 flex flex-col gap-4 hover:-translate-y-1 transition">
    <div class="aspect-square rounded-2xl bg-gray-50 flex items-center justify-center overflow-hidden">
        @if ($image)
            <img src="{{ $image }}" alt="{{ $product->name }}" class="h-full w-full object-cover">
        @else
            <span class="text-xs uppercase tracking-[0.3em] text-gray-400">gear-in</span>
        @endif
    </div>
    <div class="space-y-2">
        <p class="text-xs uppercase tracking-[0.4em] text-gray-400">{{ $product->category->name }}</p>
        <a href="{{ route('products.show', $product) }}" class="text-lg font-semibold block">{{ $product->name }}</a>
        <p class="text-sm text-gray-500">{{ $product->summary }}</p>
        <p class="text-base font-semibold">{{ $product->formatted_price }}</p>
    </div>
    <a href="{{ route('products.show', $product) }}" class="mt-auto inline-flex items-center justify-center border border-gray-900 text-gray-900 px-4 py-2 rounded-full text-xs uppercase tracking-[0.4em] hover:bg-gray-900 hover:text-white transition">Detail</a>
</div>

