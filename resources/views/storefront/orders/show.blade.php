@extends('layouts.storefront')
@php
    use Illuminate\Support\Str;
@endphp

@section('title', 'Pesanan '.$order->code.' · gear-in')

@section('content')
    <section class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-14 space-y-8">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs uppercase tracking-[0.5em] text-gray-400">Kode Pesanan</p>
                <h1 class="text-3xl font-semibold">{{ $order->code }}</h1>
                <p class="text-sm text-gray-500">{{ $order->created_at->format('d M Y, H:i') }}</p>
            </div>
            <x-status-badge :status="$order->status" />
        </div>

        <div class="grid sm:grid-cols-3 gap-6">
            <div class="bg-white border border-gray-200 rounded-3xl p-6 space-y-3">
                <p class="text-xs uppercase tracking-[0.5em] text-gray-400">Pengiriman</p>
                <p class="font-semibold">{{ $order->customer_name }}</p>
                <p class="text-sm text-gray-500">{{ $order->customer_phone }}</p>
                <p class="text-sm text-gray-500">{{ $order->address_line1 }}, {{ $order->city }} {{ $order->postal_code }}</p>
            </div>
            <div class="bg-white border border-gray-200 rounded-3xl p-6 space-y-3">
                <p class="text-xs uppercase tracking-[0.5em] text-gray-400">Ringkasan</p>
                <div class="flex justify-between text-sm text-gray-500">
                    <span>Subtotal</span>
                    <span>{{ 'Rp '.number_format($order->subtotal, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between text-sm text-gray-500">
                    <span>Shipping</span>
                    <span>{{ 'Rp '.number_format($order->shipping_fee, 0, ',', '.') }}</span>
                </div>
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
            </div>
        </div>

        <div class="bg-white border border-gray-200 rounded-[32px] p-6 space-y-4">
            <p class="text-xs uppercase tracking-[0.5em] text-gray-400">Daftar Produk</p>
            @foreach ($order->items as $item)
                <div class="flex items-center justify-between border-b border-gray-100 pb-4 last:pb-0 last:border-none">
                    <div>
                        <p class="text-xs uppercase tracking-[0.4em] text-gray-400">{{ $item->product_name }}</p>
                        <p class="text-sm text-gray-500">x{{ $item->quantity }}</p>
                    </div>
                    <p class="font-semibold">{{ 'Rp '.number_format($item->line_total, 0, ',', '.') }}</p>
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
@endsection

