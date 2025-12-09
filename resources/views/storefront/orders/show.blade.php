@extends('layouts.storefront')
@php
    use Illuminate\Support\Str;
@endphp

@section('title', 'Pesanan '.$order->code.' · gear-in')

@section('content')
    <section class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-14 space-y-6 sm:space-y-8">
        <div>
            <a href="{{ route('orders.index') }}" class="inline-flex items-center gap-2 text-sm text-gray-500 hover:text-gray-900 mb-4 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
                Kembali ke Riwayat Pesanan
            </a>
        </div>
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <p class="text-xs uppercase tracking-[0.5em] text-gray-400">Kode Pesanan</p>
                <h1 class="text-2xl sm:text-3xl font-semibold">{{ $order->code }}</h1>
                <p class="text-sm text-gray-500">{{ $order->created_at->format('d M Y, H:i') }}</p>
            </div>
            <div class="flex flex-wrap items-center gap-3 sm:gap-4">
                <a href="{{ route('orders.receipt', $order) }}" target="_blank" class="px-4 sm:px-5 py-2 rounded-full bg-gray-900 text-white text-xs uppercase tracking-[0.4em] hover:bg-black transition">
                    Print Receipt
                </a>
                @if(in_array($order->status, [\App\Models\Order::STATUS_PENDING, \App\Models\Order::STATUS_PAID]))
                    <form id="cancelOrderForm" action="{{ route('orders.cancel', $order) }}" method="POST">
                        @csrf
                        <button type="submit" class="px-4 sm:px-5 py-2 rounded-full bg-red-600 text-white text-xs uppercase tracking-[0.4em] hover:bg-red-700 transition">
                            Cancel Order
                        </button>
                    </form>
                @endif
                <x-status-badge :status="$order->status" />
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
            <div class="bg-white border border-gray-200 rounded-3xl p-6 space-y-3">
                <p class="text-xs uppercase tracking-[0.5em] text-gray-400">Pengiriman</p>
                <p class="font-semibold">{{ $order->customer_name }}</p>
                <p class="text-sm text-gray-500">{{ $order->customer_phone }}</p>
                <p class="text-sm text-gray-500">{{ $order->address_line1 }}, {{ $order->city }} {{ $order->postal_code }}</p>
            </div>
            <div class="bg-white border border-gray-200 rounded-3xl p-6 space-y-3">
                <p class="text-xs uppercase tracking-[0.5em] text-gray-400">Ringkasan</p>
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
                <div class="flex justify-between text-sm text-gray-500">
                    <span>Subtotal</span>
                    <span>{{ 'Rp '.number_format($order->subtotal, 0, ',', '.') }}</span>
                </div>
                @if($productDiscountsTotal > 0)
                    <div class="flex justify-between text-sm">
                        <span class="text-red-600">Diskon Produk</span>
                        <span class="text-red-600">-{{ 'Rp '.number_format($productDiscountsTotal, 0, ',', '.') }}</span>
                    </div>
                @endif
                <div class="flex justify-between text-sm text-gray-500">
                    <span>Pengiriman</span>
                    <span>{{ 'Rp '.number_format($order->shipping_fee, 0, ',', '.') }}</span>
                </div>
                @if($order->discount > 0)
                    <div class="flex justify-between text-sm">
                        <span class="text-red-600">Diskon Voucher{{ $order->voucher ? ' (' . $order->voucher->code . ')' : '' }}</span>
                        <span class="text-red-600">-{{ 'Rp '.number_format($order->discount, 0, ',', '.') }}</span>
                    </div>
                @endif
                <div class="flex justify-between text-lg font-semibold border-t border-gray-100 pt-4">
                    <span>Total</span>
                    <span>{{ 'Rp '.number_format($order->total, 0, ',', '.') }}</span>
                </div>
            </div>
            <div class="bg-white border border-gray-200 rounded-3xl p-6 space-y-3">
                <p class="text-xs uppercase tracking-[0.5em] text-gray-400">Metode</p>
                <p class="text-sm text-gray-600">Pembayaran: <span class="font-semibold text-gray-900 text-base">{{ Str::headline($order->payment_method) }}</span></p>
                <p class="text-sm text-gray-600">Status Pembayaran: <span class="font-semibold text-gray-900">{{ Str::headline($order->payment_status) }}</span></p>
                <p class="text-sm text-gray-600">Kurir: <span class="font-semibold text-gray-900">{{ Str::headline($order->shipping_method) }}</span></p>
                @if($order->tracking_number)
                    <p class="text-sm text-gray-600">Tracking Number: <span class="font-semibold text-gray-900">{{ $order->tracking_number }}</span></p>
                @endif
            </div>
        </div>

        <div class="bg-white border border-gray-200 rounded-[32px] p-6 space-y-4">
            <p class="text-xs uppercase tracking-[0.5em] text-gray-400">Daftar Produk</p>
            @foreach ($order->items as $item)
                @php
                    $product = \App\Models\Product::find($item->product_id);
                    $hasReview = $reviews->has($item->product_id);
                    $review = $hasReview ? $reviews->get($item->product_id) : null;
                @endphp
                <div class="border-b border-gray-100 pb-4 last:pb-0 last:border-none space-y-3">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs uppercase tracking-[0.4em] text-gray-400">{{ $item->product_name }}</p>
                            @if($item->variant_name)
                                <p class="text-sm text-gray-600 font-medium">{{ $item->variant_name }}</p>
                            @endif
                            <p class="text-sm text-gray-500">x{{ $item->quantity }}</p>
                        </div>
                        <p class="font-semibold">{{ 'Rp '.number_format($item->line_total, 0, ',', '.') }}</p>
                    </div>
                    
                    @if($order->status === \App\Models\Order::STATUS_COMPLETED && $product)
                        @if($hasReview && $review)
                            <div class="bg-gray-50 rounded-2xl p-4 space-y-2">
                                <div class="flex items-center gap-2">
                                    <div class="flex items-center gap-1">
                                        @for($i = 1; $i <= 5; $i++)
                                            <span class="text-sm {{ $i <= $review->rating ? 'text-yellow-500' : 'text-gray-300' }}">★</span>
                                        @endfor
                                    </div>
                                    <span class="text-xs text-gray-500">{{ $review->created_at->format('d M Y') }}</span>
                                </div>
                                @if($review->comment)
                                    <p class="text-sm text-gray-700">{{ $review->comment }}</p>
                                @endif
                                @if($review->admin_reply)
                                    <div class="mt-3 pt-3 border-t border-gray-200">
                                        <p class="text-xs text-gray-500 mb-1">Balasan Admin:</p>
                                        <p class="text-sm text-gray-700 font-medium">{{ $review->admin_reply }}</p>
                                        @if($review->adminRepliedBy)
                                            <p class="text-xs text-gray-500 mt-1">— {{ $review->adminRepliedBy->name }}, {{ $review->admin_replied_at->format('d M Y') }}</p>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        @else
                            <div class="bg-gray-50 rounded-2xl p-4">
                                <p class="text-xs uppercase tracking-[0.4em] text-gray-400 mb-3">Beri Rating & Review</p>
                                <form action="{{ route('reviews.store', $product) }}" method="POST" class="space-y-3">
                                    @csrf
                                    <input type="hidden" name="order_id" value="{{ $order->id }}">
                                    <div>
                                        <label for="order-review-rating-{{ $product->id }}" class="text-xs text-gray-500 mb-1 block">Rating</label>
                                        <select name="rating" id="order-review-rating-{{ $product->id }}" class="w-full rounded-2xl border border-gray-300 px-4 py-2 text-gray-900 focus:border-gray-900 focus:ring-gray-900" required>
                                            <option value="5">5 - Excellent</option>
                                            <option value="4">4 - Very Good</option>
                                            <option value="3">3 - Good</option>
                                            <option value="2">2 - Fair</option>
                                            <option value="1">1 - Poor</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="text-xs text-gray-500 mb-1 block">Komentar (Opsional)</label>
                                        <textarea name="comment" rows="3" class="w-full rounded-2xl border border-gray-300 px-4 py-2 text-gray-900 focus:border-gray-900 focus:ring-gray-900" placeholder="Bagikan pengalaman Anda..."></textarea>
                                    </div>
                                    <button type="submit" class="w-full px-4 py-2 rounded-full bg-gray-900 text-white text-xs uppercase tracking-[0.4em] hover:bg-black transition">Kirim Review</button>
                                </form>
                            </div>
                        @endif
                    @endif
                </div>
            @endforeach
        </div>

        <div class="bg-white border border-gray-200 rounded-[32px] p-6 space-y-4">
            <p class="text-xs uppercase tracking-[0.5em] text-gray-400">Timeline Pesanan</p>
            <ol class="space-y-4">
                @foreach ($order->statusHistories as $history)
                    <li class="flex items-start gap-4">
                        <div class="w-2 h-2 rounded-full bg-gray-900 mt-2"></div>
                        <div>
                            <p class="text-sm font-semibold text-gray-900">{{ Str::headline($history->status) }}</p>
                            <p class="text-xs text-gray-500">{{ $history->created_at->format('d M Y, H:i') }}</p>
                            @if ($history->user)
                                <p class="text-xs text-gray-500">oleh {{ $history->user->name }}</p>
                            @endif
                            @if ($history->note)
                                <p class="text-xs text-gray-500 mt-1">{{ $history->note }}</p>
                            @endif
                        </div>
                    </li>
                @endforeach
            </ol>
        </div>
    </section>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const cancelForm = document.getElementById('cancelOrderForm');
            if (cancelForm) {
                cancelForm.addEventListener('submit', async function(e) {
                    e.preventDefault();
                    const confirmed = await window.customConfirm(
                        'Apakah Anda yakin ingin membatalkan pesanan ini?',
                        'Batalkan Pesanan'
                    );
                    if (confirmed) {
                        this.submit();
                    }
                });
            }
        });
    </script>
    @endpush
@endsection

