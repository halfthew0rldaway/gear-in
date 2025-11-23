@extends('layouts.admin')

@section('page-title', 'Dashboard')

@section('content')
    <div class="grid md:grid-cols-2 xl:grid-cols-4 gap-4">
        <div class="bg-white border border-gray-200 rounded-3xl p-5">
            <p class="text-xs uppercase tracking-[0.4em] text-gray-400">Orders Today</p>
            <p class="text-3xl font-semibold">{{ $stats['orders_today'] }}</p>
        </div>
        <div class="bg-white border border-gray-200 rounded-3xl p-5">
            <p class="text-xs uppercase tracking-[0.4em] text-gray-400">Total Orders</p>
            <p class="text-3xl font-semibold">{{ $stats['total_orders'] }}</p>
        </div>
        <div class="bg-white border border-gray-200 rounded-3xl p-5">
            <p class="text-xs uppercase tracking-[0.4em] text-gray-400">Products</p>
            <p class="text-3xl font-semibold">{{ $stats['products'] }}</p>
        </div>
        <div class="bg-white border border-gray-200 rounded-3xl p-5">
            <p class="text-xs uppercase tracking-[0.4em] text-gray-400">Revenue</p>
            <p class="text-3xl font-semibold">{{ 'Rp '.number_format($stats['revenue'], 0, ',', '.') }}</p>
        </div>
    </div>

    <div class="bg-white border border-gray-200 rounded-[32px] p-6">
        <div class="flex items-center justify-between mb-4">
            <div>
                <p class="text-xs uppercase tracking-[0.4em] text-gray-400">Pesanan Terbaru</p>
                <h2 class="text-xl font-semibold">5 order terakhir</h2>
            </div>
            <a href="{{ route('admin.orders.index') }}" class="text-xs uppercase tracking-[0.4em] text-gray-400 hover:text-gray-900">Lihat semua</a>
        </div>
        <div class="divide-y divide-gray-100">
            @forelse ($recentOrders as $order)
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 py-4">
                    <div>
                        <p class="text-sm font-semibold">{{ $order->code }}</p>
                        <p class="text-xs text-gray-500">{{ $order->user->name }}</p>
                    </div>
                    <p class="font-semibold">{{ 'Rp '.number_format($order->total, 0, ',', '.') }}</p>
                    <x-status-badge :status="$order->status" />
                </div>
            @empty
                <p class="text-sm text-gray-500 py-4">Belum ada pesanan.</p>
            @endforelse
        </div>
    </div>
@endsection

