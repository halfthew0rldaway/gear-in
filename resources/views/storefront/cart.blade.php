@extends('layouts.storefront')

@section('title', 'Keranjang · gear-in')

@section('content')
    <section class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-14 space-y-8">
        <div>
            <p class="text-xs uppercase tracking-[0.5em] text-gray-400">Keranjang</p>
            <h1 class="text-4xl font-semibold">Ringkasan barang</h1>
        </div>

        <div class="bg-white border border-gray-200 rounded-[32px] p-4 sm:p-6 space-y-4 sm:space-y-6">
            @forelse ($cartItems as $item)
                <div class="grid grid-cols-1 sm:grid-cols-12 gap-4 border-b border-gray-100 pb-4 sm:pb-5 last:border-none last:pb-0">
                    <div class="sm:col-span-5">
                        <p class="text-xs uppercase tracking-[0.4em] text-gray-400">{{ $item->product->category->name }}</p>
                        <h2 class="text-base sm:text-lg font-semibold mt-1">{{ $item->product->name }}</h2>
                        @if($item->variant)
                            <p class="text-sm text-gray-600 font-medium mt-1">{{ $item->variant->name }}</p>
                        @endif
                        <p class="text-xs sm:text-sm text-gray-500 mt-1 line-clamp-2">{{ $item->product->summary }}</p>
                    </div>
                    <div class="sm:col-span-4 flex flex-col sm:flex-row sm:items-center gap-3">
                        <form action="{{ route('cart.update', $item) }}" method="POST" class="flex items-center gap-2">
                            @csrf
                            @method('PATCH')
                            <label class="text-xs text-gray-500 sm:hidden">Jumlah:</label>
                            <input type="number" min="1" max="{{ $item->variant ? $item->variant->stock : $item->product->stock }}" name="quantity" value="{{ $item->quantity }}" class="w-20 rounded-full border border-gray-200 px-4 py-2 text-center text-sm focus:border-gray-900 focus:ring-gray-900">
                            <button type="submit" class="text-xs uppercase tracking-[0.4em] text-gray-500 hover:text-gray-900 transition">Perbarui</button>
                        </form>
                        <form action="{{ route('cart.destroy', $item) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-xs uppercase tracking-[0.4em] text-red-500 hover:text-red-600 transition">Hapus</button>
                        </form>
                    </div>
                    <div class="sm:col-span-3 flex items-center justify-between sm:justify-end">
                        <p class="text-sm sm:text-base font-semibold">
                            @php
                                $price = $item->product->price;
                                if ($item->variant) {
                                    $price += $item->variant->price_adjustment;
                                }
                            @endphp
                            {{ 'Rp '.number_format($price * $item->quantity, 0, ',', '.') }}
                        </p>
                    </div>
                </div>
            @empty
                <p class="text-sm text-gray-500 text-center py-8">Keranjang masih kosong.</p>
            @endforelse
        </div>

        <div class="grid sm:grid-cols-2 gap-6">
            <div class="bg-white border border-gray-200 rounded-3xl p-6 space-y-2">
                <div class="flex justify-between text-sm text-gray-500">
                    <span>Subtotal</span>
                    <span>{{ 'Rp '.number_format($subtotal, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between text-sm text-gray-500">
                    <span>Pengiriman</span>
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

