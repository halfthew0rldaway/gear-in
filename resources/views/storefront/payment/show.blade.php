@extends('layouts.storefront')

@section('title', 'Pembayaran · gear-in')

@section('content')
    <section class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-14 space-y-8">
        <div>
            <a href="{{ route('orders.show', $order) }}" class="inline-flex items-center gap-2 text-sm text-gray-500 hover:text-gray-900 mb-4 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
                Kembali ke Detail Pesanan
            </a>
            <p class="text-xs uppercase tracking-[0.5em] text-gray-400">Pembayaran</p>
            <h1 class="text-4xl font-semibold">Lakukan Pembayaran</h1>
            <p class="text-sm text-gray-600 mt-2">Kode Pesanan: <span class="font-semibold">{{ $order->code }}</span></p>
        </div>

        <div class="bg-white border border-gray-200 rounded-[32px] p-6 sm:p-8 space-y-6">
            <!-- Order Summary -->
            <div class="border-b border-gray-200 pb-6">
                <h2 class="text-lg font-semibold mb-4">Ringkasan Pesanan</h2>
                <div class="space-y-2 text-sm">
                    @php
                        // Calculate product discounts from order items
                        $productDiscountsTotal = 0;
                        foreach ($order->items as $item) {
                            $product = \App\Models\Product::find($item->product_id);
                            if ($product && $product->hasActiveDiscount()) {
                                $basePrice = $product->price;
                                if ($item->variant_id) {
                                    $variant = \App\Models\ProductVariant::find($item->variant_id);
                                    if ($variant) {
                                        $basePrice += $variant->price_adjustment;
                                    }
                                }
                                if ($product->hasActiveDiscount()) {
                                    $discount = $basePrice * ($product->discount_percentage / 100);
                                    $productDiscountsTotal += $discount * $item->quantity;
                                }
                            }
                        }
                    @endphp
                    <div class="flex justify-between">
                        <span class="text-gray-600">Subtotal</span>
                        <span class="font-semibold">{{ 'Rp '.number_format($order->subtotal, 0, ',', '.') }}</span>
                    </div>
                    @if($productDiscountsTotal > 0)
                        <div class="flex justify-between">
                            <span class="text-red-600">Diskon Produk</span>
                            <span class="font-semibold text-red-600">-{{ 'Rp '.number_format($productDiscountsTotal, 0, ',', '.') }}</span>
                        </div>
                    @endif
                    <div class="flex justify-between">
                        <span class="text-gray-600">Biaya Pengiriman</span>
                        <span class="font-semibold">{{ 'Rp '.number_format($order->shipping_fee, 0, ',', '.') }}</span>
                    </div>
                    @if($order->discount > 0)
                        <div class="flex justify-between">
                            <span class="text-red-600">Diskon Voucher{{ $order->voucher ? ' (' . $order->voucher->code . ')' : '' }}</span>
                            <span class="font-semibold text-red-600">-{{ 'Rp '.number_format($order->discount, 0, ',', '.') }}</span>
                        </div>
                    @endif
                    <div class="flex justify-between text-lg font-semibold border-t border-gray-200 pt-4">
                        <span>Total Pembayaran</span>
                        <span>{{ 'Rp '.number_format($order->total, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>

            <!-- Payment Method -->
            @if($order->payment_method === 'qris')
                <!-- QRIS Payment -->
                <div class="space-y-4">
                    <div>
                        <h2 class="text-lg font-semibold mb-2">Scan QR Code untuk Pembayaran</h2>
                        <p class="text-sm text-gray-600">Gunakan aplikasi e-wallet atau mobile banking untuk scan QR code di bawah ini</p>
                    </div>
                    
                    <!-- QR Code Dummy -->
                    <div class="flex justify-center">
                        <div class="bg-white border-2 border-gray-200 rounded-2xl p-6 inline-block">
                            <!-- QR Code Pattern Dummy dengan pattern yang mirip QR code asli -->
                            <div class="w-64 h-64 bg-white rounded-xl relative overflow-hidden shadow-inner" id="qr-code-container">
                                <svg class="w-full h-full" viewBox="0 0 256 256" xmlns="http://www.w3.org/2000/svg">
                                    <!-- Background putih -->
                                    <rect width="256" height="256" fill="white"/>
                                    
                                    <!-- Finder Patterns (3 kotak besar di pojok) -->
                                    <!-- Top Left -->
                                    <rect x="20" y="20" width="60" height="60" fill="black"/>
                                    <rect x="30" y="30" width="40" height="40" fill="white"/>
                                    <rect x="40" y="40" width="20" height="20" fill="black"/>
                                    
                                    <!-- Top Right -->
                                    <rect x="176" y="20" width="60" height="60" fill="black"/>
                                    <rect x="186" y="30" width="40" height="40" fill="white"/>
                                    <rect x="196" y="40" width="20" height="20" fill="black"/>
                                    
                                    <!-- Bottom Left -->
                                    <rect x="20" y="176" width="60" height="60" fill="black"/>
                                    <rect x="30" y="186" width="40" height="40" fill="white"/>
                                    <rect x="40" y="196" width="20" height="20" fill="black"/>
                                    
                                    <!-- Alignment Pattern (kotak kecil di tengah) -->
                                    <rect x="108" y="108" width="40" height="40" fill="black"/>
                                    <rect x="118" y="118" width="20" height="20" fill="white"/>
                                    <rect x="123" y="123" width="10" height="10" fill="black"/>
                                    
                                    <!-- Data Pattern (random pattern untuk terlihat seperti QR code) -->
                                    <!-- Baris 1 -->
                                    <rect x="100" y="20" width="6" height="6" fill="black"/>
                                    <rect x="120" y="20" width="6" height="6" fill="black"/>
                                    <rect x="140" y="20" width="6" height="6" fill="black"/>
                                    <rect x="100" y="40" width="6" height="6" fill="black"/>
                                    <rect x="120" y="40" width="6" height="6" fill="black"/>
                                    <rect x="140" y="40" width="6" height="6" fill="black"/>
                                    
                                    <!-- Baris 2 -->
                                    <rect x="20" y="100" width="6" height="6" fill="black"/>
                                    <rect x="40" y="100" width="6" height="6" fill="black"/>
                                    <rect x="60" y="100" width="6" height="6" fill="black"/>
                                    <rect x="20" y="120" width="6" height="6" fill="black"/>
                                    <rect x="40" y="120" width="6" height="6" fill="black"/>
                                    <rect x="60" y="120" width="6" height="6" fill="black"/>
                                    
                                    <!-- Baris 3 -->
                                    <rect x="100" y="100" width="6" height="6" fill="black"/>
                                    <rect x="120" y="100" width="6" height="6" fill="black"/>
                                    <rect x="140" y="100" width="6" height="6" fill="black"/>
                                    <rect x="160" y="100" width="6" height="6" fill="black"/>
                                    <rect x="100" y="120" width="6" height="6" fill="black"/>
                                    <rect x="120" y="120" width="6" height="6" fill="black"/>
                                    <rect x="140" y="120" width="6" height="6" fill="black"/>
                                    <rect x="160" y="120" width="6" height="6" fill="black"/>
                                    
                                    <rect x="180" y="100" width="6" height="6" fill="black"/>
                                    <rect x="200" y="100" width="6" height="6" fill="black"/>
                                    <rect x="220" y="100" width="6" height="6" fill="black"/>
                                    <rect x="180" y="120" width="6" height="6" fill="black"/>
                                    <rect x="200" y="120" width="6" height="6" fill="black"/>
                                    <rect x="220" y="120" width="6" height="6" fill="black"/>
                                    
                                    <!-- Baris 4 -->
                                    <rect x="100" y="140" width="6" height="6" fill="black"/>
                                    <rect x="120" y="140" width="6" height="6" fill="black"/>
                                    <rect x="140" y="140" width="6" height="6" fill="black"/>
                                    <rect x="160" y="140" width="6" height="6" fill="black"/>
                                    <rect x="180" y="140" width="6" height="6" fill="black"/>
                                    <rect x="200" y="140" width="6" height="6" fill="black"/>
                                    
                                    <rect x="20" y="140" width="6" height="6" fill="black"/>
                                    <rect x="40" y="140" width="6" height="6" fill="black"/>
                                    <rect x="60" y="140" width="6" height="6" fill="black"/>
                                    
                                    <!-- Baris 5 -->
                                    <rect x="100" y="160" width="6" height="6" fill="black"/>
                                    <rect x="120" y="160" width="6" height="6" fill="black"/>
                                    <rect x="140" y="160" width="6" height="6" fill="black"/>
                                    <rect x="160" y="160" width="6" height="6" fill="black"/>
                                    
                                    <rect x="20" y="160" width="6" height="6" fill="black"/>
                                    <rect x="40" y="160" width="6" height="6" fill="black"/>
                                    <rect x="60" y="160" width="6" height="6" fill="black"/>
                                    
                                    <!-- Baris 6 -->
                                    <rect x="100" y="180" width="6" height="6" fill="black"/>
                                    <rect x="120" y="180" width="6" height="6" fill="black"/>
                                    <rect x="140" y="180" width="6" height="6" fill="black"/>
                                    <rect x="160" y="180" width="6" height="6" fill="black"/>
                                    <rect x="180" y="180" width="6" height="6" fill="black"/>
                                    <rect x="200" y="180" width="6" height="6" fill="black"/>
                                    <rect x="220" y="180" width="6" height="6" fill="black"/>
                                    
                                    <rect x="20" y="200" width="6" height="6" fill="black"/>
                                    <rect x="40" y="200" width="6" height="6" fill="black"/>
                                    <rect x="60" y="200" width="6" height="6" fill="black"/>
                                    <rect x="20" y="220" width="6" height="6" fill="black"/>
                                    <rect x="40" y="220" width="6" height="6" fill="black"/>
                                    <rect x="60" y="220" width="6" height="6" fill="black"/>
                                    
                                    <!-- Baris 7 -->
                                    <rect x="100" y="200" width="6" height="6" fill="black"/>
                                    <rect x="120" y="200" width="6" height="6" fill="black"/>
                                    <rect x="140" y="200" width="6" height="6" fill="black"/>
                                    <rect x="160" y="200" width="6" height="6" fill="black"/>
                                    <rect x="180" y="200" width="6" height="6" fill="black"/>
                                    <rect x="200" y="200" width="6" height="6" fill="black"/>
                                    <rect x="220" y="200" width="6" height="6" fill="black"/>
                                    
                                    <rect x="100" y="220" width="6" height="6" fill="black"/>
                                    <rect x="120" y="220" width="6" height="6" fill="black"/>
                                    <rect x="140" y="220" width="6" height="6" fill="black"/>
                                    <rect x="160" y="220" width="6" height="6" fill="black"/>
                                    <rect x="180" y="220" width="6" height="6" fill="black"/>
                                    <rect x="200" y="220" width="6" height="6" fill="black"/>
                                    <rect x="220" y="220" width="6" height="6" fill="black"/>
                                    
                                    <!-- Baris 8 -->
                                    <rect x="20" y="240" width="6" height="6" fill="black"/>
                                    <rect x="40" y="240" width="6" height="6" fill="black"/>
                                    <rect x="60" y="240" width="6" height="6" fill="black"/>
                                    <rect x="100" y="240" width="6" height="6" fill="black"/>
                                    <rect x="120" y="240" width="6" height="6" fill="black"/>
                                    <rect x="140" y="240" width="6" height="6" fill="black"/>
                                    <rect x="160" y="240" width="6" height="6" fill="black"/>
                                    <rect x="180" y="240" width="6" height="6" fill="black"/>
                                    <rect x="200" y="240" width="6" height="6" fill="black"/>
                                    <rect x="220" y="240" width="6" height="6" fill="black"/>
                                    
                                    <!-- Timing Patterns (garis putus-putus) -->
                                    <rect x="90" y="20" width="4" height="4" fill="black"/>
                                    <rect x="95" y="20" width="4" height="4" fill="white"/>
                                    <rect x="100" y="20" width="4" height="4" fill="black"/>
                                    
                                    <rect x="20" y="90" width="4" height="4" fill="black"/>
                                    <rect x="20" y="95" width="4" height="4" fill="white"/>
                                    <rect x="20" y="100" width="4" height="4" fill="black"/>
                                </svg>
                            </div>
                            <p class="text-center text-xs text-gray-500 mt-4 font-semibold uppercase tracking-wide">QRIS</p>
                            <p class="text-center text-xs text-gray-400 mt-1 font-mono">{{ $order->code }}</p>
                        </div>
                    </div>

                    <div class="bg-blue-50 border border-blue-200 rounded-2xl p-4">
                        <p class="text-sm text-blue-800">
                            <strong>Instruksi:</strong> Scan QR code di atas menggunakan aplikasi e-wallet (Dana, OVO, GoPay, LinkAja) atau mobile banking yang mendukung QRIS.
                        </p>
                    </div>
                </div>

            @elseif($order->payment_method === 'bank_transfer')
                <!-- Bank Transfer -->
                <div class="space-y-4">
                    <div>
                        <h2 class="text-lg font-semibold mb-2">Transfer Bank (Virtual Account)</h2>
                        <p class="text-sm text-gray-600">Lakukan transfer ke salah satu rekening di bawah ini</p>
                    </div>

                    <div class="space-y-4">
                        @foreach($bankAccounts as $bankCode => $bank)
                            <div class="border border-gray-200 rounded-2xl p-5 hover:border-gray-900 transition">
                                <div class="flex items-start justify-between mb-3">
                                    <div>
                                        <h3 class="font-semibold text-lg">{{ $bank['name'] }}</h3>
                                        <p class="text-xs text-gray-500">Virtual Account</p>
                                    </div>
                                </div>
                                <div class="space-y-2 text-sm">
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Nomor VA:</span>
                                        <span class="font-mono font-semibold text-gray-900">{{ $bank['va'] }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Nama Rekening:</span>
                                        <span class="font-semibold">{{ $bank['account_name'] }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Nomor Rekening:</span>
                                        <span class="font-mono font-semibold text-gray-900">{{ $bank['account'] }}</span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="bg-yellow-50 border border-yellow-200 rounded-2xl p-4">
                        <p class="text-sm text-yellow-800">
                            <strong>Penting:</strong> Pastikan nominal transfer sesuai dengan total pembayaran. Setelah transfer, klik tombol "Selesaikan Pembayaran" di bawah.
                        </p>
                    </div>
                </div>

            @elseif($order->payment_method === 'ewallet')
                <!-- E-Wallet -->
                <div class="space-y-4">
                    <div>
                        <h2 class="text-lg font-semibold mb-2">E-Wallet Payment</h2>
                        <p class="text-sm text-gray-600">Pilih metode e-wallet yang ingin digunakan</p>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div class="border border-gray-200 rounded-2xl p-5 hover:border-gray-900 transition text-center">
                            <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-3">
                                <span class="text-2xl font-bold text-green-600">D</span>
                            </div>
                            <h3 class="font-semibold mb-2">Dana</h3>
                            <p class="text-xs text-gray-500">ID: 081234567890</p>
                        </div>
                        <div class="border border-gray-200 rounded-2xl p-5 hover:border-gray-900 transition text-center">
                            <div class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-3">
                                <span class="text-2xl font-bold text-purple-600">O</span>
                            </div>
                            <h3 class="font-semibold mb-2">OVO</h3>
                            <p class="text-xs text-gray-500">ID: 081234567890</p>
                        </div>
                        <div class="border border-gray-200 rounded-2xl p-5 hover:border-gray-900 transition text-center">
                            <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-3">
                                <span class="text-2xl font-bold text-blue-600">G</span>
                            </div>
                            <h3 class="font-semibold mb-2">GoPay</h3>
                            <p class="text-xs text-gray-500">ID: 081234567890</p>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Complete Payment Button -->
            <form action="{{ route('payment.complete', $order) }}" method="POST" class="pt-6 border-t border-gray-200">
                @csrf
                <button type="submit" class="w-full px-6 py-4 rounded-full bg-gray-900 text-white text-sm uppercase tracking-[0.4em] hover:bg-black transition font-semibold">
                    Selesaikan Pembayaran
                </button>
                <p class="text-xs text-center text-gray-500 mt-3">
                    Klik tombol di atas setelah Anda melakukan pembayaran
                </p>
            </form>
        </div>
    </section>
@endsection

