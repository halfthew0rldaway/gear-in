@extends('layouts.admin')

@section('page-title', 'Pesanan')

@section('content')
    <div class="bg-white border border-gray-200 rounded-[32px] overflow-hidden">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left text-xs uppercase tracking-[0.4em] text-gray-400">
                    <th class="px-6 py-4">Kode</th>
                    <th class="px-6 py-4">Customer</th>
                    <th class="px-6 py-4">Total</th>
                    <th class="px-6 py-4">Status</th>
                    <th class="px-6 py-4 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach ($orders as $order)
                    <tr>
                        <td class="px-6 py-4">{{ $order->code }}</td>
                        <td class="px-6 py-4">
                            <p class="font-semibold">{{ $order->user->name }}</p>
                            <p class="text-xs text-gray-500">{{ $order->customer_email }}</p>
                        </td>
                        <td class="px-6 py-4">{{ 'Rp '.number_format($order->total, 0, ',', '.') }}</td>
                        <td class="px-6 py-4">
                            <x-status-badge :status="$order->status" />
                        </td>
                        <td class="px-6 py-4 text-right">
                            <a href="{{ route('admin.orders.show', $order) }}" class="text-xs uppercase tracking-[0.4em] text-gray-500 hover:text-gray-900">Detail</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    {{ $orders->links() }}
@endsection

