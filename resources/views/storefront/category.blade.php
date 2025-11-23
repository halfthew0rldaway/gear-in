@extends('layouts.storefront')

@section('title', 'Kategori '.$category->name.' · gear-in')

@section('content')
    <section class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-16 space-y-8">
        <div class="space-y-3">
            <p class="text-xs uppercase tracking-[0.5em] text-gray-400">Kategori</p>
            <h1 class="text-4xl font-semibold">{{ $category->name }}</h1>
            <p class="text-sm text-gray-500 max-w-3xl">{{ $category->description }}</p>
        </div>
        <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
            @forelse ($products as $product)
                <x-product.card :product="$product" />
            @empty
                <p class="text-sm text-gray-500">Belum ada produk pada kategori ini.</p>
            @endforelse
        </div>
        <div>
            {{ $products->links() }}
        </div>
    </section>
@endsection

