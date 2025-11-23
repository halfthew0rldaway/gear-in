@extends('layouts.storefront')

@section('title', 'gear-in · curated gaming gear')

@section('content')
    <section class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-16 space-y-16">
        <div class="grid lg:grid-cols-[1.1fr_0.9fr] gap-8 items-center">
            <div class="space-y-8">
                <p class="text-xs uppercase tracking-[0.5em] text-gray-400">Tugas Besar · Laravel E-commerce</p>
                <h1 class="text-4xl sm:text-5xl font-semibold leading-tight">Kurasi perangkat gaming dengan estetika super clean dan performa kompetitif.</h1>
                <p class="text-base text-gray-500 max-w-2xl">gear-in menghadirkan katalog hardware, aksesori, dan game premium tanpa gimmick RGB. Fokus pada material matte, garis tegas, dan pengalaman belanja yang minimalis.</p>
                <div class="flex flex-wrap gap-4">
                    <a href="{{ route('catalog') }}" class="px-6 py-3 rounded-full bg-gray-900 text-white text-xs uppercase tracking-[0.4em]">Lihat Produk</a>
                    @auth
                        @if (auth()->user()->isCustomer())
                            <a href="{{ route('checkout.index') }}" class="px-6 py-3 rounded-full border border-gray-900 text-gray-900 text-xs uppercase tracking-[0.4em]">Checkout</a>
                        @else
                            <a href="{{ route('admin.dashboard') }}" class="px-6 py-3 rounded-full border border-gray-900 text-gray-900 text-xs uppercase tracking-[0.4em]">Panel Admin</a>
                        @endif
                    @else
                        <a href="{{ route('login') }}" class="px-6 py-3 rounded-full border border-gray-200 text-gray-500 text-xs uppercase tracking-[0.4em]">Masuk</a>
                    @endauth
                </div>
            </div>
            <div class="bg-white border border-gray-200 rounded-[32px] p-8 space-y-6">
                <p class="text-xs uppercase tracking-[0.5em] text-gray-400">Kategori</p>
                <div class="flex flex-col gap-4">
                    @foreach ($categories as $category)
                        <a href="{{ route('categories.show', $category) }}" class="flex items-center justify-between border border-gray-200 rounded-2xl px-4 py-3 hover:border-gray-900 transition">
                            <div>
                                <p class="text-sm uppercase tracking-[0.3em] text-gray-400">{{ $category->name }}</p>
                                <p class="text-lg font-semibold">{{ $category->products_count }} produk</p>
                            </div>
                            <span class="text-xs uppercase tracking-[0.4em] text-gray-400">explore</span>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>

        <div id="featured" class="space-y-8">
            <div class="flex items-baseline justify-between">
                <div>
                    <p class="text-xs uppercase tracking-[0.5em] text-gray-400">Kurasi</p>
                    <h2 class="text-2xl font-semibold">Produk unggulan</h2>
                </div>
                <a href="{{ route('cart.index') }}" class="text-xs uppercase tracking-[0.5em] text-gray-400 hover:text-gray-900">Keranjang</a>
            </div>
            <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                @forelse ($featuredProducts as $product)
                    <x-product.card :product="$product" />
                @empty
                    <p class="text-sm text-gray-500">Belum ada produk unggulan.</p>
                @endforelse
            </div>
        </div>

        <div class="space-y-6">
            <div>
                <p class="text-xs uppercase tracking-[0.5em] text-gray-400">Release</p>
                <h2 class="text-2xl font-semibold">Rilisan terbaru</h2>
            </div>
            <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                @forelse ($newArrivals as $product)
                    <div class="bg-white border border-gray-200 rounded-2xl p-5 flex flex-col gap-3">
                        <p class="text-xs uppercase tracking-[0.4em] text-gray-400">{{ $product->category->name }}</p>
                        <a href="{{ route('products.show', $product) }}" class="text-lg font-semibold">{{ $product->name }}</a>
                        <p class="text-sm text-gray-500">{{ $product->summary }}</p>
                        <div class="flex items-center justify-between">
                            <span class="font-semibold">{{ $product->formatted_price }}</span>
                            @auth
                                <form action="{{ route('cart.store') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                                    <input type="hidden" name="quantity" value="1">
                                    <button class="text-xs uppercase tracking-[0.4em] border border-gray-900 px-4 py-2 rounded-full hover:bg-gray-900 hover:text-white transition">+ Keranjang</button>
                                </form>
                            @else
                                <a href="{{ route('login') }}" class="text-xs uppercase tracking-[0.4em] border border-gray-200 px-4 py-2 rounded-full text-gray-500 hover:text-gray-900">Masuk</a>
                            @endauth
                        </div>
                    </div>
                @empty
                    <p class="text-sm text-gray-500">Belum ada produk terbaru.</p>
                @endforelse
            </div>
        </div>
    </section>
@endsection

