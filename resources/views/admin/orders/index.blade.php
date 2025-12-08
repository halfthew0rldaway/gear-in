@extends('layouts.admin')

@section('page-title', 'Pesanan')

@section('content')
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-semibold">Pesanan</h1>
        <div class="flex items-center gap-4">
            <form action="{{ route('admin.orders.index') }}" method="GET" class="relative">
                <input type="search" name="q" value="{{ request('q') }}" placeholder="Cari pesanan..." class="w-64 rounded-full border-gray-300 focus:border-gray-900 focus:ring-gray-900 text-sm focus-ring">
                <button type="submit" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 hover:text-gray-900 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </button>
            </form>
        </div>
    </div>
    <div class="bg-white border border-gray-200 rounded-[32px] overflow-hidden">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left text-xs uppercase tracking-[0.4em] text-gray-400">
                    <th class="px-6 py-4">
                        <a href="{{ route('admin.orders.index', array_merge(request()->except(['sort_by', 'sort_order']), ['sort_by' => 'code', 'sort_order' => request('sort_by') == 'code' && request('sort_order') == 'asc' ? 'desc' : 'asc'])) }}" class="flex items-center gap-1 hover:text-gray-900 transition link-underline">
                            Kode
                            @if(request('sort_by') == 'code')
                                @if(request('sort_order') == 'asc')
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path></svg>
                                @else
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                @endif
                            @endif
                        </a>
                    </th>
                    <th class="px-6 py-4">
                        <a href="{{ route('admin.orders.index', array_merge(request()->except(['sort_by', 'sort_order']), ['sort_by' => 'customer', 'sort_order' => request('sort_by') == 'customer' && request('sort_order') == 'asc' ? 'desc' : 'asc'])) }}" class="flex items-center gap-1 hover:text-gray-900 transition link-underline">
                            Customer
                            @if(request('sort_by') == 'customer')
                                @if(request('sort_order') == 'asc')
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path></svg>
                                @else
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                @endif
                            @endif
                        </a>
                    </th>
                    <th class="px-6 py-4">
                        <a href="{{ route('admin.orders.index', array_merge(request()->except(['sort_by', 'sort_order']), ['sort_by' => 'total', 'sort_order' => request('sort_by') == 'total' && request('sort_order') == 'asc' ? 'desc' : 'asc'])) }}" class="flex items-center gap-1 hover:text-gray-900 transition link-underline">
                            Total
                            @if(request('sort_by') == 'total')
                                @if(request('sort_order') == 'asc')
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path></svg>
                                @else
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                @endif
                            @endif
                        </a>
                    </th>
                    <th class="px-6 py-4">
                        <a href="{{ route('admin.orders.index', array_merge(request()->except(['sort_by', 'sort_order']), ['sort_by' => 'status', 'sort_order' => request('sort_by') == 'status' && request('sort_order') == 'asc' ? 'desc' : 'asc'])) }}" class="flex items-center gap-1 hover:text-gray-900 transition link-underline">
                            Status
                            @if(request('sort_by') == 'status')
                                @if(request('sort_order') == 'asc')
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path></svg>
                                @else
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                @endif
                            @endif
                        </a>
                    </th>
                    <th class="px-6 py-4">
                        <a href="{{ route('admin.orders.index', array_merge(request()->except(['sort_by', 'sort_order']), ['sort_by' => 'date', 'sort_order' => request('sort_by') == 'date' && request('sort_order') == 'asc' ? 'desc' : 'asc'])) }}" class="flex items-center gap-1 hover:text-gray-900 transition link-underline">
                            Tanggal
                            @if(request('sort_by') == 'date' || !request('sort_by'))
                                @php
                                    $currentOrder = request('sort_by') == 'date' ? request('sort_order', 'desc') : 'desc';
                                @endphp
                                @if($currentOrder == 'asc')
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path></svg>
                                @else
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                @endif
                            @endif
                        </a>
                    </th>
                    <th class="px-6 py-4 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse ($orders as $order)
                    <tr class="scroll-reveal">
                        <td class="px-6 py-4">
                            <p class="font-semibold">{{ $order->code }}</p>
                        </td>
                        <td class="px-6 py-4">
                            <p class="font-semibold">{{ $order->user->name }}</p>
                            <p class="text-xs text-gray-500">{{ $order->customer_email }}</p>
                        </td>
                        <td class="px-6 py-4">{{ 'Rp '.number_format($order->total, 0, ',', '.') }}</td>
                        <td class="px-6 py-4">
                            <x-status-badge :status="$order->status" />
                        </td>
                        <td class="px-6 py-4">
                            <p class="text-xs text-gray-500">{{ $order->created_at->format('d M Y') }}</p>
                            <p class="text-xs text-gray-400">{{ $order->created_at->format('H:i') }}</p>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <a href="{{ route('admin.orders.show', $order) }}" class="text-xs uppercase tracking-[0.4em] text-gray-500 hover:text-gray-900 link-underline focus-ring">Detail</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-8 text-center text-sm text-gray-500">
                            @if(request('q'))
                                Tidak ada pesanan yang ditemukan untuk "{{ request('q') }}".
                            @else
                                Belum ada pesanan.
                            @endif
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    {{ $orders->links() }}
@endsection

