@props(['product'])

@php
    $images = $product->images;
    $hasImages = $images->count() > 0;
    $isSoldOut = $product->stock == 0;
    $firstImage = $hasImages ? \Illuminate\Support\Facades\Storage::url($images->first()->image_path) : null;
    $hasVariants = $product->variants->count() > 0;
    $variants = $product->variants;
    $hasRating = $product->approvedReviews->count() > 0;
@endphp

<div class="group bg-white border border-gray-200 rounded-3xl p-5 flex flex-col gap-4 hover:-translate-y-1 hover:shadow-lg hover:shadow-gray-200/50 transition-all duration-300 product-card h-full">
    <div class="aspect-square rounded-2xl bg-gray-50 flex items-center justify-center overflow-hidden relative product-image-container flex-shrink-0">
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
    <div class="space-y-2 flex-1 flex flex-col">
        <p class="text-xs uppercase tracking-[0.4em] text-gray-400">{{ $product->category->name }}</p>
        <a href="{{ route('products.show', $product) }}" class="text-lg font-semibold block line-clamp-2 min-h-[3.5rem]">{{ $product->name }}</a>
        <p class="text-sm text-gray-500 line-clamp-2 flex-1">{{ $product->summary }}</p>
        <div class="flex items-center justify-between pt-1 mt-auto">
            <p class="text-base font-semibold">{{ $product->formatted_price }}</p>
            @if($hasRating)
                <div class="flex items-center gap-1">
                    <span class="text-yellow-500 text-sm rating-star">★</span>
                    <span class="text-xs font-semibold text-gray-700">{{ number_format($product->average_rating, 1) }}</span>
                </div>
            @else
                <div class="w-12"></div>
            @endif
        </div>
    </div>
    <div class="mt-auto flex-shrink-0 min-h-[44px] flex items-end">
        @auth
            @if(!$isSoldOut)
                @if($hasVariants)
                    <!-- Product with variants - dropdown selection -->
                    <form action="{{ route('cart.store') }}" method="POST" class="quick-add-cart-form variant-form w-full" data-product-id="{{ $product->id }}">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <input type="hidden" name="quantity" value="1">
                        <div class="space-y-2">
                            <label for="variant-{{ $product->id }}" class="sr-only">Pilih Varian</label>
                            <select name="variant_id" id="variant-{{ $product->id }}" class="w-full rounded-full border border-gray-300 px-4 py-2 text-xs text-gray-900 focus:border-gray-900 focus:ring-gray-900 bg-white h-10" required>
                                <option value="">Pilih Varian</option>
                                @foreach($variants as $variant)
                                    <option value="{{ $variant->id }}" {{ $variant->stock == 0 ? 'disabled' : '' }}>
                                        {{ $variant->name }}
                                        @if($variant->price_adjustment != 0)
                                            ({{ $variant->price_adjustment > 0 ? '+' : '' }}{{ 'Rp '.number_format($variant->price_adjustment, 0, ',', '.') }})
                                        @endif
                                        {{ $variant->stock == 0 ? '- Stok Habis' : '' }}
                                    </option>
                                @endforeach
                            </select>
                            <button type="submit" class="w-full inline-flex items-center justify-center bg-gray-900 text-white px-4 py-2.5 rounded-full text-xs uppercase tracking-[0.4em] hover:bg-black transition btn-ripple focus-ring h-10">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                                <span class="quick-add-text">Keranjang</span>
                            </button>
                        </div>
                    </form>
                @else
                    <!-- Product without variants - quick add to cart -->
                    <form action="{{ route('cart.store') }}" method="POST" class="quick-add-cart-form w-full" data-product-id="{{ $product->id }}">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <input type="hidden" name="quantity" value="1">
                        <button type="submit" class="w-full inline-flex items-center justify-center bg-gray-900 text-white px-4 py-2.5 rounded-full text-xs uppercase tracking-[0.4em] hover:bg-black transition btn-ripple focus-ring h-10">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                            <span class="quick-add-text">Keranjang</span>
                        </button>
                    </form>
                @endif
            @else
                <!-- Sold out - show detail button instead -->
                <a href="{{ route('products.show', $product) }}" class="w-full inline-flex items-center justify-center border border-gray-300 text-gray-600 px-4 py-2.5 rounded-full text-xs uppercase tracking-[0.4em] hover:border-gray-900 hover:text-gray-900 transition h-10">
                    Detail
                </a>
            @endif
        @else
            <!-- Not logged in - show detail button -->
            <a href="{{ route('products.show', $product) }}" class="w-full inline-flex items-center justify-center border border-gray-900 text-gray-900 px-4 py-2.5 rounded-full text-xs uppercase tracking-[0.4em] hover:bg-gray-900 hover:text-white transition h-10">
                Detail
            </a>
        @endauth
    </div>
</div>

