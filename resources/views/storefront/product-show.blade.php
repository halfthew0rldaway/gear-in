@extends('layouts.storefront')

@section('title', $product->name.' · gear-in')

@section('content')
    <section class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-16 grid lg:grid-cols-2 gap-10">
        <div class="bg-white border border-gray-200 rounded-[32px] p-6">
            <div class="aspect-square rounded-2xl bg-gray-50 flex items-center justify-center overflow-hidden">
                @php
                    $image = $product->image_path;
                    if ($image && ! \Illuminate\Support\Str::startsWith($image, ['http://', 'https://'])) {
                        $image = \Illuminate\Support\Facades\Storage::url($image);
                    }
                @endphp
                @if ($image)
                    <img src="{{ $image }}" alt="{{ $product->name }}" class="h-full w-full object-cover" />
                @else
                    <span class="text-xs uppercase tracking-[0.3em] text-gray-400">gear-in</span>
                @endif
            </div>
        </div>
        <div class="space-y-8">
            <div class="space-y-3">
                <p class="text-xs uppercase tracking-[0.5em] text-gray-400">{{ $product->category->name }}</p>
                <h1 class="text-4xl font-semibold">{{ $product->name }}</h1>
                <p class="text-sm text-gray-500">{{ $product->summary }}</p>
                <p class="text-2xl font-semibold">{{ $product->formatted_price }}</p>
            </div>
            <div class="space-y-4 text-gray-600 leading-relaxed">
                {!! nl2br(e($product->description)) !!}
            </div>
            <div class="flex items-center gap-6">
                @auth
                    <form action="{{ route('cart.store') }}" method="POST" class="flex items-center gap-4">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <label class="text-xs uppercase tracking-[0.4em] text-gray-400">Jumlah</label>
                        <input type="number" min="1" max="{{ $product->stock }}" name="quantity" value="1" class="w-20 rounded-full border border-gray-200 px-4 py-2 text-center text-sm focus:border-gray-900 focus:ring-gray-900">
                        <button class="px-6 py-3 rounded-full bg-gray-900 text-white text-xs uppercase tracking-[0.4em] hover:bg-black transition">+ Keranjang</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="px-6 py-3 rounded-full border border-gray-900 text-gray-900 text-xs uppercase tracking-[0.4em] hover:bg-gray-900 hover:text-white transition">Masuk untuk membeli</a>
                @endauth
                <p class="text-sm text-gray-500">{{ $product->stock }} unit tersedia</p>
            </div>
            <div class="space-y-2">
                <p class="text-xs uppercase tracking-[0.5em] text-gray-400">Produk terkait</p>
                <div class="grid gap-4 sm:grid-cols-2">
                    @forelse ($relatedProducts as $related)
                        <a href="{{ route('products.show', $related) }}" class="p-4 border border-gray-200 rounded-2xl hover:border-gray-900 transition">
                            <p class="text-xs uppercase tracking-[0.4em] text-gray-400">{{ $related->category->name }}</p>
                            <p class="font-semibold">{{ $related->name }}</p>
                            <p class="text-sm text-gray-500">{{ $related->formatted_price }}</p>
                        </a>
                    @empty
                        <p class="text-sm text-gray-500">Tidak ada produk terkait.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </section>
@endsection

