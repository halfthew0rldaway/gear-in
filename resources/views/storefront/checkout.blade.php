@extends('layouts.storefront')

@section('title', 'Checkout · gear-in')

@section('content')
    <section class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-14 grid lg:grid-cols-[1fr_0.8fr] gap-10">
        <div class="space-y-6 scroll-reveal">
            <div>
                <p class="text-xs uppercase tracking-[0.5em] text-gray-400">Checkout</p>
                <h1 class="text-3xl font-semibold">Detail pengiriman</h1>
            </div>
            <form action="{{ route('checkout.store') }}" method="POST" class="bg-white border border-gray-200 rounded-[32px] p-6 space-y-4" id="checkoutForm">
                @csrf
                @if(!empty($selectedItemIds ?? []))
                    @foreach($selectedItemIds as $itemId)
                        <input type="hidden" name="selected_items[]" value="{{ $itemId }}">
                    @endforeach
                @endif
                <div class="grid sm:grid-cols-2 gap-4">
                    <label class="text-xs uppercase tracking-[0.4em] text-gray-600 block">
                        Nama penerima
                        <input type="text" name="customer_name" value="{{ old('customer_name', $user->name) }}" class="mt-2 w-full rounded-2xl border border-gray-300 px-4 py-3 text-gray-900 focus:border-gray-900 focus:ring-gray-900 focus-ring">
                    </label>
                    <label class="text-xs uppercase tracking-[0.4em] text-gray-600 block">
                        Email
                        <input type="email" name="customer_email" value="{{ old('customer_email', $user->email) }}" class="mt-2 w-full rounded-2xl border border-gray-300 px-4 py-3 text-gray-900 focus:border-gray-900 focus:ring-gray-900 focus-ring">
                    </label>
                </div>
                <div class="grid sm:grid-cols-2 gap-4">
                    <label class="text-xs uppercase tracking-[0.4em] text-gray-600 block">
                        Nomor telepon
                        <input type="tel" name="customer_phone" value="{{ old('customer_phone') }}" inputmode="numeric" pattern="[0-9]*" maxlength="15" class="mt-2 w-full rounded-2xl border border-gray-300 px-4 py-3 text-gray-900 focus:border-gray-900 focus:ring-gray-900 focus-ring">
                    </label>
                    <label class="text-xs uppercase tracking-[0.4em] text-gray-600 block">
                        Kota
                        <input type="text" name="city" value="{{ old('city') }}" class="mt-2 w-full rounded-2xl border border-gray-300 px-4 py-3 text-gray-900 focus:border-gray-900 focus:ring-gray-900 focus-ring">
                    </label>
                </div>
                <label class="text-xs uppercase tracking-[0.4em] text-gray-600 block">
                    Alamat
                    <input type="text" name="address_line1" value="{{ old('address_line1') }}" class="mt-2 w-full rounded-2xl border border-gray-300 px-4 py-3 text-gray-900 focus:border-gray-900 focus:ring-gray-900 focus-ring">
                </label>
                <label class="text-xs uppercase tracking-[0.4em] text-gray-600 block">
                    Detail tambahan
                    <input type="text" name="address_line2" value="{{ old('address_line2') }}" class="mt-2 w-full rounded-2xl border border-gray-300 px-4 py-3 text-gray-900 focus:border-gray-900 focus:ring-gray-900 focus-ring">
                </label>
                <div class="grid sm:grid-cols-2 gap-4">
                    <label class="text-xs uppercase tracking-[0.4em] text-gray-600 block">
                        Kode pos
                        <input type="text" name="postal_code" value="{{ old('postal_code') }}" inputmode="numeric" pattern="[0-9]*" maxlength="10" class="mt-2 w-full rounded-2xl border border-gray-300 px-4 py-3 text-gray-900 focus:border-gray-900 focus:ring-gray-900 focus-ring">
                    </label>
                    <label class="text-xs uppercase tracking-[0.4em] text-gray-600 block">
                        Catatan
                        <input type="text" name="notes" value="{{ old('notes') }}" class="mt-2 w-full rounded-2xl border border-gray-300 px-4 py-3 text-gray-900 focus:border-gray-900 focus:ring-gray-900 focus-ring">
                    </label>
                </div>
                <div class="grid sm:grid-cols-2 gap-4">
                    <label class="text-xs uppercase tracking-[0.4em] text-gray-600 block">
                        Metode Pembayaran
                        <select name="payment_method" class="mt-2 w-full rounded-2xl border border-gray-300 px-4 py-3 text-gray-900 focus:border-gray-900 focus:ring-gray-900 focus-ring">
                            @foreach ($paymentOptions as $value => $label)
                                <option value="{{ $value }}" @selected(old('payment_method', array_key_first($paymentOptions)) === $value)>{{ $label }}</option>
                            @endforeach
                        </select>
                    </label>
                    <label class="text-xs uppercase tracking-[0.4em] text-gray-600 block">
                        Metode Pengiriman
                        <select name="shipping_method" class="mt-2 w-full rounded-2xl border border-gray-300 px-4 py-3 text-gray-900 focus:border-gray-900 focus:ring-gray-900 focus-ring">
                            @foreach ($shippingOptions as $value => $option)
                                <option value="{{ $value }}" @selected(old('shipping_method', array_key_first($shippingOptions)) === $value)>
                                    {{ $option['label'] }} · {{ 'Rp '.number_format($option['fee'], 0, ',', '.') }}
                                </option>
                            @endforeach
                        </select>
                    </label>
                </div>
                <button type="submit" class="w-full px-6 py-3 rounded-full bg-gray-900 text-white text-xs uppercase tracking-[0.4em] hover:bg-black transition btn-ripple focus-ring">Buat pesanan</button>
            </form>
        </div>

        <div class="space-y-6 scroll-reveal">
            <div>
                <p class="text-xs uppercase tracking-[0.5em] text-gray-400">Ringkasan</p>
                <h2 class="text-2xl font-semibold">Keranjang</h2>
            </div>
            <div class="bg-white border border-gray-200 rounded-[32px] p-6 space-y-4">
                @foreach ($cartItems as $item)
                    @php
                        $price = $item->product->price;
                        if ($item->variant) {
                            $price += $item->variant->price_adjustment;
                        }
                    @endphp
                    <div class="grid grid-cols-12 gap-4 items-center border-b border-gray-100 pb-4 last:border-none last:pb-0">
                        <div class="col-span-7">
                            <p class="text-xs uppercase tracking-[0.4em] text-gray-400">{{ $item->product->category->name }}</p>
                            <p class="text-sm font-semibold mt-1">{{ $item->product->name }}</p>
                            @if($item->variant)
                                <p class="text-xs text-gray-500 mt-0.5">{{ $item->variant->name }}</p>
                            @endif
                        </div>
                        <div class="col-span-2 text-center">
                            <p class="text-xs text-gray-500">× {{ $item->quantity }}</p>
                        </div>
                        <div class="col-span-3 text-right">
                            <p class="text-sm font-semibold">{{ 'Rp '.number_format($price * $item->quantity, 0, ',', '.') }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="bg-white border border-gray-200 rounded-3xl p-6 space-y-2">
                <div class="flex justify-between text-sm text-gray-500">
                    <span>Subtotal</span>
                    <span>{{ 'Rp '.number_format($subtotal, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between text-sm text-gray-500">
                    <span>Pengiriman</span>
                    <span>{{ 'Rp '.number_format($shipping, 0, ',', '.') }}</span>
                </div>
                <p class="text-xs text-gray-500">Total akhir menyesuaikan metode pengiriman yang dipilih.</p>
                <div class="flex justify-between text-lg font-semibold border-t border-gray-100 pt-4">
                    <span>Total</span>
                    <span>{{ 'Rp '.number_format($total, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>
    </section>

    @push('scripts')
    <script>
        // Form validation dengan shake animation
        document.getElementById('checkoutForm')?.addEventListener('submit', function(e) {
            const requiredFields = this.querySelectorAll('[required]');
            let isValid = true;
            
            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    isValid = false;
                    if (window.shakeElement) {
                        window.shakeElement(field);
                    }
                    field.classList.add('border-red-500');
                } else {
                    field.classList.remove('border-red-500');
                }
            });
            
            if (!isValid) {
                e.preventDefault();
                if (window.showToast) {
                    window.showToast('Mohon lengkapi semua field yang wajib diisi', 'error');
                }
            }
        });
    </script>
    @endpush
@endsection

