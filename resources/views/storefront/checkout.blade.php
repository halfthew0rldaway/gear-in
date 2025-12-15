@extends('layouts.storefront')

@section('title', 'Checkout · gear-in')

@section('content')
    <section class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-14 grid lg:grid-cols-[1fr_0.8fr] gap-10">
        <div class="space-y-6 scroll-reveal">
            <div>
                <p class="text-xs uppercase tracking-[0.5em] text-gray-400">Checkout</p>
                <h1 class="text-3xl font-semibold">Detail pengiriman</h1>
            </div>
            <form action="{{ route('checkout.store') }}" method="POST"
                class="bg-white border border-gray-200 rounded-[32px] p-6 space-y-4" id="checkoutForm">
                @csrf
                @if(!empty($selectedItemIds ?? []))
                    @foreach($selectedItemIds as $itemId)
                        <input type="hidden" name="selected_items[]" value="{{ $itemId }}">
                    @endforeach
                @endif
                <div class="grid sm:grid-cols-2 gap-4">
                    <label class="text-xs uppercase tracking-[0.4em] text-gray-600 block">
                        Nama penerima
                        <input type="text" name="customer_name" value="{{ old('customer_name', $user->name) }}"
                            class="mt-2 w-full rounded-2xl border border-gray-300 px-4 py-3 text-gray-900 focus:border-gray-900 focus:ring-gray-900 focus-ring">
                    </label>
                    <label class="text-xs uppercase tracking-[0.4em] text-gray-600 block">
                        Email
                        <input type="email" name="customer_email" value="{{ old('customer_email', $user->email) }}"
                            class="mt-2 w-full rounded-2xl border border-gray-300 px-4 py-3 text-gray-900 focus:border-gray-900 focus:ring-gray-900 focus-ring">
                    </label>
                </div>
                <div class="grid sm:grid-cols-2 gap-4">
                    <label class="text-xs uppercase tracking-[0.4em] text-gray-600 block">
                        Nomor telepon
                        <input type="tel" name="customer_phone" value="{{ old('customer_phone') }}" inputmode="numeric"
                            pattern="[0-9]*" maxlength="15"
                            class="mt-2 w-full rounded-2xl border border-gray-300 px-4 py-3 text-gray-900 focus:border-gray-900 focus:ring-gray-900 focus-ring">
                    </label>
                    <label class="text-xs uppercase tracking-[0.4em] text-gray-600 block">
                        Kota
                        <input type="text" name="city" value="{{ old('city') }}"
                            class="mt-2 w-full rounded-2xl border border-gray-300 px-4 py-3 text-gray-900 focus:border-gray-900 focus:ring-gray-900 focus-ring">
                    </label>
                </div>
                <label class="text-xs uppercase tracking-[0.4em] text-gray-600 block">
                    Alamat
                    <input type="text" name="address_line1" value="{{ old('address_line1') }}"
                        class="mt-2 w-full rounded-2xl border border-gray-300 px-4 py-3 text-gray-900 focus:border-gray-900 focus:ring-gray-900 focus-ring">
                </label>
                <label class="text-xs uppercase tracking-[0.4em] text-gray-600 block">
                    Detail tambahan
                    <input type="text" name="address_line2" value="{{ old('address_line2') }}"
                        class="mt-2 w-full rounded-2xl border border-gray-300 px-4 py-3 text-gray-900 focus:border-gray-900 focus:ring-gray-900 focus-ring">
                </label>
                <div class="grid sm:grid-cols-2 gap-4">
                    <label class="text-xs uppercase tracking-[0.4em] text-gray-600 block">
                        Kode pos
                        <input type="text" name="postal_code" value="{{ old('postal_code') }}" inputmode="numeric"
                            pattern="[0-9]*" maxlength="10"
                            class="mt-2 w-full rounded-2xl border border-gray-300 px-4 py-3 text-gray-900 focus:border-gray-900 focus:ring-gray-900 focus-ring">
                    </label>
                    <label class="text-xs uppercase tracking-[0.4em] text-gray-600 block">
                        Catatan
                        <input type="text" name="notes" value="{{ old('notes') }}"
                            class="mt-2 w-full rounded-2xl border border-gray-300 px-4 py-3 text-gray-900 focus:border-gray-900 focus:ring-gray-900 focus-ring">
                    </label>
                </div>
                <div class="grid sm:grid-cols-2 gap-4">
                    <label class="text-xs uppercase tracking-[0.4em] text-gray-600 block">
                        Metode Pembayaran
                        <select name="payment_method"
                            class="mt-2 w-full rounded-2xl border border-gray-300 px-4 py-3 text-gray-900 focus:border-gray-900 focus:ring-gray-900 focus-ring">
                            @foreach ($paymentOptions as $value => $label)
                                <option value="{{ $value }}" @selected(old('payment_method', array_key_first($paymentOptions)) === $value)>{{ $label }}</option>
                            @endforeach
                        </select>
                    </label>
                    <label class="text-xs uppercase tracking-[0.4em] text-gray-600 block">
                        Metode Pengiriman
                        <select name="shipping_method"
                            class="mt-2 w-full rounded-2xl border border-gray-300 px-4 py-3 text-gray-900 focus:border-gray-900 focus:ring-gray-900 focus-ring">
                            @foreach ($shippingOptions as $value => $option)
                                <option value="{{ $value }}" @selected(old('shipping_method', array_key_first($shippingOptions)) === $value)>
                                    {{ $option['label'] }} · {{ 'Rp ' . number_format($option['fee'], 0, ',', '.') }}
                                </option>
                            @endforeach
                        </select>
                    </label>
                </div>
                <button type="submit"
                    class="w-full px-6 py-3 rounded-full bg-gray-900 text-white text-xs uppercase tracking-[0.4em] hover:bg-black transition btn-ripple focus-ring">Buat
                    pesanan</button>
            </form>
        </div>

        <div class="space-y-6 scroll-reveal">
            <div>
                <p class="text-xs uppercase tracking-[0.5em] text-gray-400">Ringkasan</p>
                <h2 class="text-2xl font-semibold">Keranjang</h2>
            </div>
            <div class="bg-white border border-gray-200 rounded-[32px] p-8 space-y-6">
                @foreach ($cartItems as $item)
                    @php
                        $basePrice = $item->product->price;
                        if ($item->variant) {
                            $basePrice += $item->variant->price_adjustment;
                        }
                        $finalPrice = $basePrice;
                        if ($item->product->hasActiveDiscount()) {
                            $discount = $basePrice * ($item->product->discount_percentage / 100);
                            $finalPrice = $basePrice - $discount;
                        }
                        $totalPrice = $finalPrice * $item->quantity;
                        $originalTotalPrice = $basePrice * $item->quantity;
                    @endphp
                    <div class="flex items-center justify-between border-b border-gray-100 pb-6 last:border-none last:pb-0">
                        <!-- Product Info -->
                        <div class="flex-1 min-w-0 pr-6">
                            <p class="text-[10px] uppercase tracking-[0.4em] text-gray-400 mb-1">
                                {{ $item->product->category->name }}</p>
                            <h3 class="text-sm font-semibold text-gray-900 leading-relaxed">{{ $item->product->name }}</h3>
                            @if($item->variant)
                                <p class="text-xs text-gray-500 mt-1">{{ $item->variant->name }}</p>
                            @endif
                        </div>

                        <!-- Quantity -->
                        <div class="px-6 text-center">
                            <p class="text-xs text-gray-500 whitespace-nowrap">× {{ $item->quantity }}</p>
                        </div>

                        <!-- Price -->
                        <div class="text-right min-w-[140px] pl-6">
                            @if($item->product->hasActiveDiscount() && $finalPrice < $basePrice)
                                <div class="flex flex-col items-end gap-1">
                                    <p class="text-sm font-bold text-red-600 whitespace-nowrap">
                                        {{ 'Rp ' . number_format($totalPrice, 0, ',', '.') }}</p>
                                    <div class="flex items-center justify-end gap-2">
                                        <p class="text-[10px] text-gray-400 line-through whitespace-nowrap">
                                            {{ 'Rp ' . number_format($originalTotalPrice, 0, ',', '.') }}</p>
                                        <span class="text-[10px] font-bold text-red-600 bg-red-50 px-1.5 py-0.5 rounded">
                                            {{ round($item->product->discount_percentage) }}%
                                        </span>
                                    </div>
                                </div>
                            @else
                                <p class="text-sm font-semibold text-gray-900 whitespace-nowrap">
                                    {{ 'Rp ' . number_format($totalPrice, 0, ',', '.') }}</p>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
            <!-- Voucher Section -->
            <div class="bg-white border border-gray-200 rounded-3xl p-6 space-y-4">
                <p class="text-xs uppercase tracking-[0.4em] text-gray-400">Kode Voucher</p>
                <form id="voucher-form" class="flex gap-2">
                    @csrf
                    <input type="hidden" name="selected_items" value="{{ json_encode($selectedItemIds ?? []) }}">
                    <input type="text" name="voucher_code" id="voucher_code" placeholder="Masukkan kode voucher"
                        class="flex-1 rounded-2xl border border-gray-300 px-4 py-3 text-sm focus:border-gray-900 focus:ring-gray-900 focus-ring"
                        maxlength="20">
                    <button type="submit"
                        class="px-6 py-3 rounded-full bg-gray-900 text-white text-xs uppercase tracking-[0.4em] hover:bg-black transition focus-ring">
                        Terapkan
                    </button>
                </form>
                <div id="voucher-message" class="text-sm"></div>
            </div>

            <div class="bg-white border border-gray-200 rounded-3xl p-6 space-y-2">
                @php
                    // Calculate product discounts total
                    $productDiscountsTotal = 0;
                    foreach ($cartItems as $item) {
                        $basePrice = $item->product->price;
                        if ($item->variant) {
                            $basePrice += $item->variant->price_adjustment;
                        }
                        if ($item->product->hasActiveDiscount()) {
                            $discount = $basePrice * ($item->product->discount_percentage / 100);
                            $productDiscountsTotal += $discount * $item->quantity;
                        }
                    }
                @endphp
                <div class="flex justify-between text-sm text-gray-500">
                    <span>Subtotal</span>
                    <span id="checkout-subtotal">{{ 'Rp ' . number_format($subtotal, 0, ',', '.') }}</span>
                </div>
                @if($productDiscountsTotal > 0)
                    <div class="flex justify-between text-sm">
                        <span class="text-red-600">Diskon Produk</span>
                        <span id="checkout-product-discount"
                            class="text-red-600">-{{ 'Rp ' . number_format($productDiscountsTotal, 0, ',', '.') }}</span>
                    </div>
                @endif
                <div class="flex justify-between text-sm text-gray-500">
                    <span>Pengiriman</span>
                    <span id="checkout-shipping">{{ 'Rp ' . number_format($shipping, 0, ',', '.') }}</span>
                </div>
                <div id="voucher-discount-row" class="hidden flex justify-between text-sm">
                    <span class="text-red-600">Diskon Voucher</span>
                    <span id="checkout-discount" class="text-red-600">-Rp 0</span>
                </div>
                <p class="text-xs text-gray-500">Total akhir menyesuaikan metode pengiriman yang dipilih.</p>
                <div class="flex justify-between text-lg font-semibold border-t border-gray-100 pt-4">
                    <span>Total</span>
                    <span id="checkout-total">{{ 'Rp ' . number_format($total, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>
    </section>

    @push('scripts')
        <script>
            // Voucher validation
            document.getElementById('voucher-form')?.addEventListener('submit', async function (e) {
                e.preventDefault();
                const code = document.getElementById('voucher_code').value.trim().toUpperCase();
                const selectedItems = JSON.parse(this.querySelector('input[name="selected_items"]').value || '[]');

                if (!code) {
                    if (window.customAlert) {
                        window.customAlert('Masukkan kode voucher terlebih dahulu.', 'Kode Voucher Kosong');
                    }
                    return;
                }

                const formData = new FormData();
                formData.append('code', code);
                formData.append('selected_items', JSON.stringify(selectedItems));

                try {
                    const response = await fetch('{{ route('voucher.validate') }}', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        },
                        body: formData
                    });

                    const data = await response.json();
                    const messageDiv = document.getElementById('voucher-message');
                    const discountRow = document.getElementById('voucher-discount-row');

                    if (!response.ok) {
                        throw new Error(data.message || 'Terjadi kesalahan saat memvalidasi voucher.');
                    }

                    if (data.success) {
                        messageDiv.className = 'text-sm text-green-600';
                        messageDiv.textContent = data.message + (data.voucher ? ' (' + data.voucher.name + ')' : '');
                        discountRow.classList.remove('hidden');

                        // Update totals
                        const discount = parseFloat(data.discount) || 0;
                        const total = parseFloat(data.total) || 0;
                        document.getElementById('checkout-discount').textContent = '-Rp ' + discount.toLocaleString('id-ID');
                        document.getElementById('checkout-discount').classList.add('text-red-600');
                        document.getElementById('checkout-total').textContent = 'Rp ' + total.toLocaleString('id-ID');

                        // Store voucher code in form
                        const checkoutForm = document.getElementById('checkoutForm');
                        let voucherInput = checkoutForm.querySelector('input[name="voucher_code"]');
                        if (!voucherInput) {
                            voucherInput = document.createElement('input');
                            voucherInput.type = 'hidden';
                            voucherInput.name = 'voucher_code';
                            checkoutForm.appendChild(voucherInput);
                        }
                        voucherInput.value = code;
                    } else {
                        messageDiv.className = 'text-sm text-red-600';
                        messageDiv.textContent = data.message || 'Kode voucher tidak valid.';
                        discountRow.classList.add('hidden');

                        // Reset totals
                        const subtotal = {{ $subtotal }};
                        const shipping = {{ $shipping }};
                        const productDiscount = {{ $productDiscountsTotal }};
                        const total = subtotal + shipping;
                        document.getElementById('checkout-total').textContent = 'Rp ' + total.toLocaleString('id-ID');

                        // Remove voucher from form
                        const checkoutForm = document.getElementById('checkoutForm');
                        const voucherInput = checkoutForm.querySelector('input[name="voucher_code"]');
                        if (voucherInput) {
                            voucherInput.remove();
                        }
                    }
                } catch (error) {
                    console.error('Voucher validation error:', error);
                    const messageDiv = document.getElementById('voucher-message');
                    if (messageDiv) {
                        messageDiv.className = 'text-sm text-red-600';
                        messageDiv.textContent = error.message || 'Terjadi kesalahan saat memvalidasi voucher.';
                    }
                    if (window.customAlert) {
                        window.customAlert(error.message || 'Terjadi kesalahan saat memvalidasi voucher.', 'Error');
                    }
                }
            });

            // Form validation dengan shake animation
            document.getElementById('checkoutForm')?.addEventListener('submit', function (e) {
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