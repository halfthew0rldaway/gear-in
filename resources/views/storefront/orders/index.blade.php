@extends('layouts.storefront')

@section('title', 'Pesanan · gear-in')

@section('content')
    <section class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-14 space-y-6 sm:space-y-8">
        <div>
            <p class="text-xs uppercase tracking-[0.5em] text-gray-400">Pesanan</p>
            <h1 class="text-2xl sm:text-3xl font-semibold">Riwayat transaksi</h1>
        </div>

        <!-- Shopping Summary -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Total Spending Card -->
            <div class="bg-gray-900 rounded-[32px] p-8 text-white relative overflow-hidden group">
                <div
                    class="absolute top-0 right-0 w-64 h-64 bg-white/5 rounded-full -mr-16 -mt-16 blur-2xl group-hover:bg-white/10 transition duration-700">
                </div>
                <div class="relative z-10">
                    <p class="text-xs uppercase tracking-[0.4em] text-gray-200 mb-2 font-medium">Total Belanja</p>
                    <p class="text-3xl font-bold text-white tracking-tight">
                        {{ 'Rp ' . number_format($totalSpent, 0, ',', '.') }}
                    </p>
                    <div class="mt-6 flex gap-6">
                        <div>
                            <span
                                class="block text-2xl font-bold text-white tracking-tight">{{ $completedOrdersCount }}</span>
                            <span class="text-[10px] uppercase tracking-widest text-gray-200 font-medium">Selesai</span>
                        </div>
                        <div class="w-px bg-gray-700 my-1"></div>
                        <div>
                            <span class="block text-2xl font-bold text-white tracking-tight">{{ $activeOrdersCount }}</span>
                            <span class="text-[10px] uppercase tracking-widest text-gray-200 font-medium">Proses</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Last Order / Quick Action -->
            @if($lastOrder)
                <div
                    class="md:col-span-2 bg-white border border-gray-200 rounded-[32px] p-8 flex flex-col justify-between hover:shadow-lg transition duration-300">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-4">
                        <div>
                            <p class="text-xs uppercase tracking-[0.4em] text-gray-400 mb-1">Terakhir Dibeli</p>
                            <p class="font-medium text-gray-900">{{ $lastOrder->items->first()->product->name ?? 'Product' }}
                            </p>
                            @if($lastOrder->items->count() > 1)
                                <p class="text-xs text-gray-500">+{{ $lastOrder->items->count() - 1 }} item lainnya</p>
                            @endif
                        </div>
                        <a href="{{ route('orders.show', $lastOrder) }}"
                            class="inline-flex items-center justify-center px-4 py-2 bg-gray-100 hover:bg-gray-200 rounded-full text-xs font-bold uppercase tracking-widest text-gray-900 transition">
                            Beli Lagi
                        </a>
                    </div>
                    <div class="flex items-center gap-2 mt-auto pt-4 border-t border-gray-100">
                        <p class="text-xs text-gray-400">
                            Dipesan pada {{ $lastOrder->created_at->format('d M Y') }}
                        </p>
                    </div>
                </div>
            @else
                <div
                    class="md:col-span-2 bg-gray-50 border border-gray-200 rounded-[32px] p-8 flex items-center justify-center text-center">
                    <div>
                        <p class="text-gray-500 mb-2">Belum ada riwayat belanja.</p>
                        <a href="{{ route('catalog') }}" class="text-gray-900 font-medium hover:underline">Mulai Belanja</a>
                    </div>
                </div>
            @endif
        </div>

        <!-- Tabs Navigation -->
        <div class="flex flex-wrap gap-2 border-b border-gray-200">
            <button onclick="showSection('ongoing')" id="tab-ongoing"
                class="tab-button active px-4 py-2 text-sm font-medium text-gray-900 border-b-2 border-gray-900 transition focus-ring">
                Sedang Berjalan
                @if($ongoingOrders->count() > 0)
                    <span class="ml-2 px-2 py-0.5 text-xs bg-gray-100 rounded-full">{{ $ongoingOrders->count() }}</span>
                @endif
            </button>
            <button onclick="showSection('canceled')" id="tab-canceled"
                class="tab-button px-4 py-2 text-sm font-medium text-gray-500 border-b-2 border-transparent hover:text-gray-900 transition focus-ring">
                Dibatalkan
                @if($canceledOrders->count() > 0)
                    <span class="ml-2 px-2 py-0.5 text-xs bg-gray-100 rounded-full">{{ $canceledOrders->count() }}</span>
                @endif
            </button>
            <button onclick="showSection('completed')" id="tab-completed"
                class="tab-button px-4 py-2 text-sm font-medium text-gray-500 border-b-2 border-transparent hover:text-gray-900 transition focus-ring">
                Selesai
                @if($completedOrders->count() > 0)
                    <span class="ml-2 px-2 py-0.5 text-xs bg-gray-100 rounded-full">{{ $completedOrders->count() }}</span>
                @endif
            </button>
        </div>

        <!-- Ongoing Orders Section -->
        <div id="section-ongoing" class="order-section">
            <div class="bg-white border border-gray-200 rounded-[32px] divide-y divide-gray-100" data-stagger="100"
                data-stagger-selector="> a">
                @forelse ($ongoingOrders as $order)
                    <a href="{{ route('orders.show', $order) }}"
                        class="block sm:flex sm:items-center sm:justify-between gap-4 px-4 sm:px-6 py-4 sm:py-5 hover:bg-gray-50 transition focus-ring">
                        <div class="flex-1">
                            <div class="flex items-center gap-3 mb-2">
                                <p class="text-xs uppercase tracking-widest text-gray-500 font-semibold">{{ $order->code }}</p>
                                <x-status-badge :status="$order->status" />
                            </div>
                            <p class="text-sm text-gray-500 mb-1">{{ $order->created_at->format('d M Y, H:i') }}</p>
                            <p class="text-xs text-gray-400">{{ $order->items->count() }}
                                item{{ $order->items->count() > 1 ? 's' : '' }}</p>
                        </div>
                        <div class="flex items-center justify-between sm:justify-end gap-4 mt-2 sm:mt-0">
                            <p class="text-base sm:text-lg font-semibold">
                                {{ 'Rp ' . number_format($order->total, 0, ',', '.') }}
                            </p>
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </div>
                    </a>
                @empty
                    <div class="px-6 py-12 text-center">
                        <p class="text-sm text-gray-500 mb-2">Tidak ada pesanan yang sedang berjalan.</p>
                        <a href="{{ route('catalog') }}" class="text-sm text-gray-900 hover:underline">Lihat Katalog</a>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Canceled Orders Section -->
        <div id="section-canceled" class="order-section hidden">
            <div class="bg-white border border-gray-200 rounded-[32px] divide-y divide-gray-100" data-stagger="100"
                data-stagger-selector="> a">
                @forelse ($canceledOrders as $order)
                    <a href="{{ route('orders.show', $order) }}"
                        class="block sm:flex sm:items-center sm:justify-between gap-4 px-4 sm:px-6 py-4 sm:py-5 hover:bg-gray-50 transition opacity-75">
                        <div class="flex-1">
                            <div class="flex items-center gap-3 mb-2">
                                <p class="text-xs uppercase tracking-widest text-gray-500 font-semibold">{{ $order->code }}</p>
                                <x-status-badge :status="$order->status" />
                            </div>
                            <p class="text-sm text-gray-500 mb-1">{{ $order->created_at->format('d M Y, H:i') }}</p>
                            <p class="text-xs text-gray-400">{{ $order->items->count() }}
                                item{{ $order->items->count() > 1 ? 's' : '' }}</p>
                        </div>
                        <div class="flex items-center justify-between sm:justify-end gap-4 mt-2 sm:mt-0">
                            <p class="text-base sm:text-lg font-semibold line-through">
                                {{ 'Rp ' . number_format($order->total, 0, ',', '.') }}
                            </p>
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </div>
                    </a>
                @empty
                    <div class="px-6 py-12 text-center">
                        <p class="text-sm text-gray-500">Tidak ada pesanan yang dibatalkan.</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Completed Orders Section -->
        <div id="section-completed" class="order-section hidden">
            <div class="bg-white border border-gray-200 rounded-[32px] divide-y divide-gray-100" data-stagger="100"
                data-stagger-selector="> a">
                @forelse ($completedOrders as $order)
                    <div class="px-4 sm:px-6 py-4 sm:py-5 hover:bg-gray-50 transition">
                        <div class="block sm:flex sm:items-center sm:justify-between gap-4">
                            <a href="{{ route('orders.show', $order) }}" class="flex-1">
                                <div class="flex items-center gap-3 mb-2">
                                    <p class="text-xs uppercase tracking-widest text-gray-500 font-semibold">{{ $order->code }}
                                    </p>
                                    <x-status-badge :status="$order->status" />
                                </div>
                                <p class="text-sm text-gray-500 mb-1">{{ $order->created_at->format('d M Y, H:i') }}</p>
                                <p class="text-xs text-gray-400">{{ $order->items->count() }}
                                    item{{ $order->items->count() > 1 ? 's' : '' }}</p>
                            </a>
                            <div class="flex items-center justify-between sm:justify-end gap-4 mt-2 sm:mt-0">
                                <p class="text-base sm:text-lg font-semibold">
                                    {{ 'Rp ' . number_format($order->total, 0, ',', '.') }}
                                </p>
                                <a href="{{ route('orders.show', $order) }}"
                                    class="px-4 py-2 rounded-full bg-gray-900 text-white text-xs uppercase tracking-[0.4em] hover:bg-black transition">
                                    Beri Rating
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="px-6 py-12 text-center">
                        <p class="text-sm text-gray-500 mb-2">Tidak ada pesanan yang selesai.</p>
                        <a href="{{ route('catalog') }}" class="text-sm text-gray-900 hover:underline">Lihat Katalog</a>
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    @push('scripts')
        <script>
            function showSection(section) {
                // Hide all sections
                document.querySelectorAll('.order-section').forEach(el => el.classList.add('hidden'));

                // Remove active class from all tabs
                document.querySelectorAll('.tab-button').forEach(btn => {
                    btn.classList.remove('active', 'text-gray-900', 'border-gray-900');
                    btn.classList.add('text-gray-500', 'border-transparent');
                });

                // Show selected section
                document.getElementById('section-' + section).classList.remove('hidden');

                // Add active class to clicked tab
                const activeTab = document.getElementById('tab-' + section);
                activeTab.classList.add('active', 'text-gray-900', 'border-gray-900');
                activeTab.classList.remove('text-gray-500', 'border-transparent');
            }

            // Show ongoing section by default
            document.addEventListener('DOMContentLoaded', function () {
                showSection('ongoing');
            });
        </script>
    @endpush
@endsection