@extends('layouts.storefront')

@section('title', 'Wishlist · gear-in')

@section('content')
    <section class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-14 space-y-8">
        <div>
            <p class="text-xs uppercase tracking-[0.5em] text-gray-400">Wishlist</p>
            <h1 class="text-3xl font-semibold">Produk Favorit Saya</h1>
        </div>

        @if($wishlists->count() > 0)
            <div class="grid sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6" data-stagger="100" data-stagger-selector="> div">
                @foreach ($wishlists as $wishlist)
                    <div class="bg-white border border-gray-200 rounded-[32px] p-6 space-y-4 scroll-reveal h-full flex flex-col">
                        <a href="{{ route('products.show', $wishlist->product) }}" class="block flex-1 flex flex-col">
                            <div class="aspect-square rounded-2xl bg-gray-50 flex items-center justify-center overflow-hidden mb-4 flex-shrink-0">
                                @php
                                    $images = $wishlist->product->images;
                                    $hasImages = $images->count() > 0;
                                    $firstImage = $hasImages ? \Illuminate\Support\Facades\Storage::url($images->first()->image_path) : null;
                                @endphp
                                @if ($hasImages && $firstImage)
                                    <img src="{{ $firstImage }}" alt="{{ $wishlist->product->name }}" class="h-full w-full object-cover" />
                                @else
                                    <div class="w-full h-full bg-black flex items-center justify-center">
                                        <span class="text-xs uppercase tracking-[0.3em] text-white">gear-in</span>
                                    </div>
                                @endif
                            </div>
                            <div class="flex-1 flex flex-col">
                                <p class="text-xs uppercase tracking-[0.4em] text-gray-400">{{ $wishlist->product->category->name }}</p>
                                <p class="font-semibold line-clamp-2 min-h-[3rem] mt-1">{{ $wishlist->product->name }}</p>
                                <p class="text-sm text-gray-500 mt-auto pt-2">{{ $wishlist->product->formatted_price }}</p>
                            </div>
                        </a>
                        <div class="flex items-center gap-2 pt-4 border-t border-gray-100 flex-shrink-0">
                            <form action="{{ route('cart.store') }}" method="POST" class="flex-1">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $wishlist->product->id }}">
                                <input type="hidden" name="quantity" value="1">
                                <button class="w-full px-4 py-2.5 rounded-full bg-gray-900 text-white text-xs uppercase tracking-[0.4em] hover:bg-black transition btn-ripple focus-ring h-10">+ Keranjang</button>
                            </form>
                            <form action="{{ route('wishlist.destroy', $wishlist->product) }}" method="POST" class="flex-shrink-0">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="px-4 py-2.5 rounded-full border border-red-600 text-red-600 text-xs hover:bg-red-600 hover:text-white transition btn-ripple focus-ring h-10">Hapus</button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-8">
                {{ $wishlists->links() }}
            </div>
        @else
            <div class="bg-white border border-gray-200 rounded-[32px] p-12 text-center">
                <p class="text-gray-500 mb-4">Wishlist Anda masih kosong.</p>
                <a href="{{ route('catalog') }}" class="px-6 py-3 rounded-full bg-gray-900 text-white text-xs uppercase tracking-[0.4em] hover:bg-black transition inline-block">Jelajahi Produk</a>
            </div>
        @endif
    </section>
@endsection

