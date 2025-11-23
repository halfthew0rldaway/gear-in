@extends('layouts.storefront')

@section('title', 'Keranjang · gear-in')

@section('content')
    <section class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-14 space-y-8">
        <div>
            <p class="text-xs uppercase tracking-[0.5em] text-gray-400">Keranjang</p>
            <h1 class="text-4xl font-semibold">Ringkasan barang</h1>
        </div>

        <div class="bg-white border border-gray-200 rounded-[32px] p-6 space-y-6">
            @forelse ($cartItems as $item)
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-gray-100 pb-5 last:border-none last:pb-0">
                    <div>
                        <p class="text-xs uppercase tracking-[0.4em] text-gray-400">{{ $item->product->category->name }}</p>
                        <h2 class="text-lg font-semibold">{{ $item->product->name }}</h2>
                        <p class="text-sm text-gray-500">{{ $item->product->summary }}</p>
                    </div>
                    <div class="flex items-center gap-3">
                        <form action="{{ route('cart.update', $item) }}" method="POST" class="flex items-center gap-2">
                            @csrf
                            @method('PATCH')
                            <input type="number" min="1" max="{{ $item->product->stock }}" name="quantity" value="{{ $item->quantity }}" class="w-20 rounded-full border border-gray-200 px-4 py-2 text-center text-sm focus:border-gray-900 focus:ring-gray-900">
                            <button class="text-xs uppercase tracking-[0.4em] text-gray-500 hover:text-gray-900">Update</button>
                        </form>
                        <form action="{{ route('cart.destroy', $item) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button class="text-xs uppercase tracking-[0.4em] text-red-500 hover:text-red-600">Hapus</button>
                        </form>
                    </div>
                    <p class="font-semibold">{{ 'Rp '.number_format($item->product->price * $item->quantity, 0, ',', '.') }}</p>
                </div>
            @empty
                <p class="text-sm text-gray-500">Keranjang masih kosong.</p>
            @endforelse
        </div>

        <div class="grid sm:grid-cols-2 gap-6">
            <div class="bg-white border border-gray-200 rounded-3xl p-6 space-y-2">
                <div class="flex justify-between text-sm text-gray-500">
                    <span>Subtotal</span>
                    <span>{{ 'Rp '.number_format($subtotal, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between text-sm text-gray-500">
                    <span>Shipping</span>
                    <span>{{ 'Rp '.number_format($shipping, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between text-lg font-semibold border-t border-gray-100 pt-4">
                    <span>Total</span>
                    <span>{{ 'Rp '.number_format($total, 0, ',', '.') }}</span>
                </div>
            </div>

            <div class="bg-white border border-gray-200 rounded-3xl p-6 space-y-4">
                <p class="text-sm text-gray-500">Lanjutkan ke checkout untuk memasukkan detail pengiriman dan membuat pesanan.</p>
                <a href="{{ route('checkout.index') }}" class="block text-center px-6 py-3 rounded-full bg-gray-900 text-white text-xs uppercase tracking-[0.4em] hover:bg-black transition {{ $cartItems->isEmpty() ? 'pointer-events-none opacity-50' : '' }}">
                    Checkout
                </a>
            </div>
        </div>
    </section>
@endsection

