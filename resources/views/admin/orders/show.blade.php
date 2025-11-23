@extends('layouts.admin')
@php
    use Illuminate\Support\Str;
@endphp

@section('page-title', 'Detail Pesanan')

@section('content')
    <div class="bg-white border border-gray-200 rounded-[32px] p-6 space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs uppercase tracking-[0.4em] text-gray-400">Kode</p>
                <h1 class="text-2xl font-semibold">{{ $order->code }}</h1>
                <p class="text-sm text-gray-500">{{ $order->created_at->format('d M Y, H:i') }}</p>
            </div>
            <x-status-badge :status="$order->status" />
        </div>

        <form action="{{ route('admin.orders.update', $order) }}" method="POST" class="flex items-center gap-4">
            @csrf
            @method('PATCH')
            <select name="status" class="rounded-2xl border border-gray-200 px-4 py-2 focus:border-gray-900 focus:ring-gray-900">
                @foreach ([\App\Models\Order::STATUS_PENDING, \App\Models\Order::STATUS_PAID, \App\Models\Order::STATUS_SHIPPED, \App\Models\Order::STATUS_COMPLETED, \App\Models\Order::STATUS_CANCELLED] as $status)
                    <option value="{{ $status }}" @selected($order->status === $status)>{{ ucfirst($status) }}</option>
                @endforeach
            </select>
            <button class="px-5 py-2 rounded-full bg-gray-900 text-white text-xs uppercase tracking-[0.4em]">Update</button>
        </form>

        <div class="grid lg:grid-cols-3 gap-6">
            <div class="space-y-3">
                <p class="text-xs uppercase tracking-[0.4em] text-gray-400">Customer</p>
                <p class="font-semibold">{{ $order->customer_name }}</p>
                <p class="text-sm text-gray-500">{{ $order->customer_email }}</p>
                <p class="text-sm text-gray-500">{{ $order->customer_phone }}</p>
            </div>
            <div class="space-y-3">
                <p class="text-xs uppercase tracking-[0.4em] text-gray-400">Alamat</p>
                <p class="text-sm text-gray-500">{{ $order->address_line1 }}</p>
                <p class="text-sm text-gray-500">{{ $order->city }} {{ $order->postal_code }}</p>
            </div>
            <div class="space-y-3">
                <p class="text-xs uppercase tracking-[0.4em] text-gray-400">Metode</p>
                <p class="text-sm text-gray-600">Pembayaran: <span class="font-semibold text-gray-900">{{ Str::headline($order->payment_method) }}</span></p>
                <p class="text-sm text-gray-600">Status Pembayaran: <span class="font-semibold text-gray-900">{{ Str::headline($order->payment_status) }}</span></p>
                <p class="text-sm text-gray-600">Shipping: <span class="font-semibold text-gray-900">{{ Str::headline($order->shipping_method) }}</span></p>
            </div>
        </div>

        <div class="space-y-2">
            <p class="text-xs uppercase tracking-[0.4em] text-gray-400">Produk</p>
            <div class="divide-y divide-gray-100">
                @foreach ($order->items as $item)
                    <div class="flex items-center justify-between py-3">
                        <div>
                            <p class="font-semibold">{{ $item->product_name }}</p>
                            <p class="text-xs text-gray-500">x{{ $item->quantity }}</p>
                        </div>
                        <p class="font-semibold">{{ 'Rp '.number_format($item->line_total, 0, ',', '.') }}</p>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="space-y-3">
            <p class="text-xs uppercase tracking-[0.4em] text-gray-400">Timeline</p>
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
    </div>
@endsection

