@extends('layouts.admin')
@php
    use Illuminate\Support\Str;
@endphp

@section('page-title', 'Detail Pesanan')

@section('content')
    <div class="flex items-center justify-between mb-8">
        <div>
            <p class="text-xs uppercase tracking-[0.4em] text-gray-400 mb-1">Detail Pesanan</p>
            <h1 class="text-3xl font-semibold">{{ $order->code }}</h1>
            <p class="text-sm text-gray-500 mt-1">{{ $order->created_at->format('d M Y, H:i') }}</p>
        </div>
        <div class="flex items-center gap-3">
            <x-status-badge :status="$order->status" />
            <a href="{{ route('admin.orders.receipt', $order) }}" target="_blank"
                class="w-10 h-10 rounded-full border border-gray-200 flex items-center justify-center text-gray-500 hover:bg-gray-50 hover:text-gray-900 transition bg-white" title="Cetak Struk">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
            </a>
        </div>
    </div>

    <div class="grid lg:grid-cols-3 gap-8">
        <!-- Main Content (2 cols) -->
        <div class="lg:col-span-2 space-y-8">
            <!-- Order Items -->
            <div class="bg-white border border-gray-200 rounded-[32px] p-8">
                <h2 class="text-xs uppercase tracking-[0.4em] text-gray-400 mb-6">Produk</h2>
                <div class="divide-y divide-gray-100">
                    @foreach ($order->items as $item)
                        <div class="flex items-center justify-between py-4 first:pt-0 last:pb-0">
                            <div class="flex items-center gap-4">
                                @if($item->product && $item->product->images->count() > 0)
                                    <img src="{{ Storage::url($item->product->images->first()->image_path) }}" class="w-12 h-12 rounded-lg object-cover border border-gray-100">
                                @else
                                    <div class="w-12 h-12 rounded-lg bg-gray-50 border border-gray-100 flex items-center justify-center text-gray-300">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    </div>
                                @endif
                                <div>
                                    <p class="font-semibold text-gray-900">{{ $item->product_name }}</p>
                                    <p class="text-sm text-gray-500">x{{ $item->quantity }} @ {{ 'Rp ' . number_format($item->unit_price, 0, ',', '.') }}</p>
                                </div>
                            </div>
                            <p class="font-semibold text-gray-900">{{ 'Rp ' . number_format($item->line_total, 0, ',', '.') }}</p>
                        </div>
                    @endforeach
                </div>
                
                <div class="mt-8 border-t border-gray-100 pt-6 space-y-3">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500">Subtotal</span>
                        <span class="font-medium">{{ 'Rp ' . number_format($order->subtotal, 0, ',', '.') }}</span>
                    </div>
                    @if($order->discount_amount > 0)
                        <div class="flex justify-between text-sm text-green-600">
                            <span>Diskon</span>
                            <span>-{{ 'Rp ' . number_format($order->discount_amount, 0, ',', '.') }}</span>
                        </div>
                    @endif
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500">Ongkir</span>
                        <span class="font-medium">Gratis</span>
                    </div>
                    <div class="flex justify-between text-lg font-bold border-t border-gray-100 pt-4 mt-4">
                        <span>Total</span>
                        <span>{{ 'Rp ' . number_format($order->grand_total, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>

            <!-- Timeline -->
            <div class="bg-white border border-gray-200 rounded-[32px] p-8">
                <h2 class="text-xs uppercase tracking-[0.4em] text-gray-400 mb-6">Riwayat</h2>
                <div class="relative pl-4 border-l border-gray-100 space-y-8">
                    @foreach ($order->statusHistories as $history)
                        <div class="relative">
                            <div class="absolute -left-[21px] top-1.5 w-2.5 h-2.5 rounded-full border-2 border-white bg-gray-900 ring-1 ring-gray-100"></div>
                            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start gap-1">
                                <div>
                                    <p class="font-semibold text-sm text-gray-900">{{ Str::headline($history->status) }}</p>
                                    @if ($history->note)
                                        <p class="text-xs text-gray-500 mt-1">{{ $history->note }}</p>
                                    @endif
                                    @if ($history->user)
                                        <p class="text-[10px] text-gray-400 mt-1 uppercase tracking-wider">Oleh {{ $history->user->name }}</p>
                                    @endif
                                </div>
                                <span class="text-xs text-gray-400 font-mono">{{ $history->created_at->format('d M Y H:i') }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Sidebar (1 col) -->
        <div class="space-y-8">
            <!-- Management Card -->
            <div class="bg-white border border-gray-200 rounded-[32px] p-6 shadow-sm">
                <h2 class="text-xs uppercase tracking-[0.4em] text-gray-400 mb-6">Manajemen</h2>
                
                <form action="{{ route('admin.orders.update', $order) }}" method="POST" class="space-y-5">
                    @csrf
                    @method('PATCH')
                    
                    <div>
                        <label for="status" class="block text-xs font-medium text-gray-700 mb-2 uppercase tracking-wide">Status Pesanan</label>
                        <select name="status" id="status" class="w-full rounded-xl border-gray-200 text-sm focus:border-gray-900 focus:ring-gray-900">
                            @foreach ([\App\Models\Order::STATUS_PENDING, \App\Models\Order::STATUS_PAID, \App\Models\Order::STATUS_SHIPPED, \App\Models\Order::STATUS_COMPLETED, \App\Models\Order::STATUS_CANCELLED] as $status)
                                <option value="{{ $status }}" @selected($order->status === $status)>{{ ucfirst($status) }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="tracking_number" class="block text-xs font-medium text-gray-700 mb-2 uppercase tracking-wide">Nomor Resi</label>
                        <input type="text" name="tracking_number" id="tracking_number" value="{{ $order->tracking_number }}" placeholder="-" 
                            class="w-full rounded-xl border-gray-200 text-sm focus:border-gray-900 focus:ring-gray-900">
                    </div>

                    <button class="w-full py-3 rounded-full bg-gray-900 text-white text-xs uppercase tracking-[0.4em] hover:bg-black transition btn-ripple">
                        Perbarui
                    </button>
                </form>

                <div class="border-t border-gray-100 my-6 pt-6">
                    <div class="flex items-center justify-between mb-4">
                        <span class="text-xs font-medium text-gray-700 uppercase tracking-wide">Ditangani Oleh</span>
                        @if($order->handledBy)
                            <span class="text-xs font-semibold bg-gray-100 px-2 py-1 rounded text-gray-900">{{ $order->handledBy->name }}</span>
                        @else
                            <span class="text-xs text-gray-400 italic">Belum ada</span>
                        @endif
                    </div>
                    
                    <form action="{{ route('admin.orders.assign', $order) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        @if(!$order->handled_by)
                            <button name="action" value="claim" class="w-full py-2.5 rounded-full border border-gray-300 text-gray-600 text-xs uppercase tracking-[0.2em] hover:border-gray-900 hover:text-gray-900 transition">
                                Ambil Pesanan
                            </button>
                        @elseif($order->handled_by === auth()->id())
                            <button name="action" value="release" class="w-full py-2.5 rounded-full border border-red-200 text-red-500 text-xs uppercase tracking-[0.2em] hover:bg-red-50 transition">
                                Lepas Pesanan
                            </button>
                        @endif
                    </form>
                </div>
            </div>

            <!-- Customer Info -->
            <div class="bg-white border border-gray-200 rounded-[32px] p-6">
                <h2 class="text-xs uppercase tracking-[0.4em] text-gray-400 mb-6">Pelanggan</h2>
                <div class="flex items-center gap-4 mb-6">
                    <div class="w-12 h-12 rounded-full bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center text-lg font-bold text-gray-600">
                        {{ strtoupper(substr($order->customer_name, 0, 1)) }}
                    </div>
                    <div>
                        <p class="font-semibold text-gray-900">{{ $order->customer_name }}</p>
                        <p class="text-xs text-gray-500">{{ $order->customer_email }}</p>
                    </div>
                </div>
                <div class="space-y-4">
                    <div>
                        <p class="text-[10px] text-gray-400 uppercase tracking-wider mb-1">Telepon</p>
                        <p class="text-sm text-gray-700 font-mono">{{ $order->customer_phone }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] text-gray-400 uppercase tracking-wider mb-1">Alamat Pengiriman</p>
                        <p class="text-sm text-gray-700 leading-relaxed">
                            {{ $order->address_line1 }}<br>
                            {{ $order->city }}, {{ $order->postal_code }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Payment & Shipping -->
            <div class="bg-white border border-gray-200 rounded-[32px] p-6">
                <h2 class="text-xs uppercase tracking-[0.4em] text-gray-400 mb-6">Info</h2>
                <div class="space-y-4">
                    <div class="flex justify-between items-center pb-4 border-b border-gray-50">
                        <span class="text-sm text-gray-500">Metode Bayar</span>
                        <span class="text-sm font-semibold text-gray-900">{{ Str::headline($order->payment_method) }}</span>
                    </div>
                    <div class="flex justify-between items-center pb-4 border-b border-gray-50">
                        <span class="text-sm text-gray-500">Status Bayar</span>
                         <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium {{ $order->payment_status === 'paid' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                            {{ Str::headline($order->payment_status) }}
                        </span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-500">Pengiriman</span>
                        <span class="text-sm font-semibold text-gray-900">{{ Str::headline($order->shipping_method) }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection