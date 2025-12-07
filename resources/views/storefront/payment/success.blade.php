@extends('layouts.storefront')
@php
    use Illuminate\Support\Str;
@endphp

@section('title', 'Pembayaran Berhasil · gear-in')

@section('content')
    <section class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-14 space-y-8">
        <div class="text-center">
            <!-- Success Icon -->
            <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-6">
                <svg class="w-12 h-12 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
            </div>
            
            <h1 class="text-4xl font-semibold mb-3">Pembayaran Berhasil!</h1>
            <p class="text-lg text-gray-600 mb-2">Terima kasih atas pembayaran Anda</p>
            <p class="text-sm text-gray-500">Kode Pesanan: <span class="font-semibold text-gray-900">{{ $order->code }}</span></p>
        </div>

        <div class="bg-white border border-gray-200 rounded-[32px] p-6 sm:p-8 space-y-6">
            <div class="text-center space-y-2">
                <p class="text-sm text-gray-600">Total Pembayaran</p>
                <p class="text-3xl font-bold text-gray-900">{{ 'Rp '.number_format($order->total, 0, ',', '.') }}</p>
            </div>

            <div class="border-t border-gray-200 pt-6 space-y-4">
                <div class="flex justify-between text-sm">
                    <span class="text-gray-600">Metode Pembayaran</span>
                    <span class="font-semibold">{{ Str::headline($order->payment_method) }}</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-gray-600">Status</span>
                    <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-xs font-semibold">Lunas</span>
                </div>
            </div>

            <div class="bg-gray-50 rounded-2xl p-5 space-y-3">
                <h3 class="font-semibold text-sm">Apa selanjutnya?</h3>
                <ul class="space-y-2 text-sm text-gray-600">
                    <li class="flex items-start gap-2">
                        <span class="text-green-600 mt-0.5">✓</span>
                        <span>Pesanan Anda sedang diproses oleh tim kami</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <span class="text-green-600 mt-0.5">✓</span>
                        <span>Anda akan menerima notifikasi saat pesanan dikirim</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <span class="text-green-600 mt-0.5">✓</span>
                        <span>Tracking number akan tersedia setelah pesanan dikirim</span>
                    </li>
                </ul>
            </div>

            <div class="flex flex-col sm:flex-row gap-3 pt-4">
                <a href="{{ route('orders.show', $order) }}" class="flex-1 px-6 py-3 rounded-full bg-gray-900 text-white text-sm uppercase tracking-[0.4em] hover:bg-black transition text-center font-semibold">
                    Kembali ke Detail Pesanan
                </a>
                <a href="{{ route('orders.index') }}" class="flex-1 px-6 py-3 rounded-full border border-gray-900 text-gray-900 text-sm uppercase tracking-[0.4em] hover:bg-gray-900 hover:text-white transition text-center font-semibold">
                    Lihat Semua Pesanan
                </a>
            </div>
        </div>
    </section>
@endsection

