@props(['product'])

@php
    $images = $product->images;
    $hasImages = $images->count() > 0;
    $isSoldOut = $product->stock == 0;
    $firstImage = $hasImages ? \Illuminate\Support\Facades\Storage::url($images->first()->image_path) : null;
@endphp

<div class="group bg-white border border-gray-200 rounded-3xl p-5 flex flex-col gap-4 hover:-translate-y-1 transition">
    <div class="aspect-square rounded-2xl bg-gray-50 flex items-center justify-center overflow-hidden relative">
        @if($isSoldOut)
            <!-- Sold Out Overlay -->
            <div class="absolute inset-0 bg-black bg-opacity-60 flex items-center justify-center z-10">
                <div class="text-center">
                    <p class="text-2xl font-bold text-white mb-1">STOK HABIS</p>
                    <p class="text-xs text-gray-200">Stok habis</p>
                </div>
            </div>
        @endif

        @if ($hasImages && $firstImage)
            <img src="{{ $firstImage }}" alt="{{ $product->name }}" class="h-full w-full object-cover">
        @else
            <!-- Default gear-in placeholder (black background) -->
            <div class="w-full h-full bg-black flex items-center justify-center">
                <span class="text-xs uppercase tracking-[0.3em] text-white">gear-in</span>
            </div>
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

