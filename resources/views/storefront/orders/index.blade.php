@extends('layouts.storefront')

@section('title', 'Pesanan · gear-in')

@section('content')
    <section class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-14 space-y-8">
        <div>
            <p class="text-xs uppercase tracking-[0.5em] text-gray-400">Pesanan</p>
            <h1 class="text-3xl font-semibold">Riwayat transaksi</h1>
        </div>
        <div class="bg-white border border-gray-200 rounded-[32px] divide-y divide-gray-100">
            @forelse ($orders as $order)
                <a href="{{ route('orders.show', $order) }}" class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 px-6 py-5 hover:bg-gray-50 transition">
                    <div>
                        <p class="text-xs uppercase tracking-[0.4em] text-gray-400">{{ $order->code }}</p>
                        <p class="text-sm text-gray-500">{{ $order->created_at->format('d M Y, H:i') }}</p>
                    </div>
                    <p class="font-semibold">{{ 'Rp '.number_format($order->total, 0, ',', '.') }}</p>
                    <x-status-badge :status="$order->status" />
                </a>
            @empty
                <p class="px-6 py-5 text-sm text-gray-500">Belum ada pesanan.</p>
            @endforelse
        </div>
        {{ $orders->links() }}
    </section>
@endsection

