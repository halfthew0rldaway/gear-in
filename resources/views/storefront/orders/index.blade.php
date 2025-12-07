@extends('layouts.storefront')

@section('title', 'Pesanan · gear-in')

@section('content')
    <section class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-14 space-y-6 sm:space-y-8">
        <div>
            <p class="text-xs uppercase tracking-[0.5em] text-gray-400">Pesanan</p>
            <h1 class="text-2xl sm:text-3xl font-semibold">Riwayat transaksi</h1>
        </div>

        <!-- Tabs Navigation -->
        <div class="flex flex-wrap gap-2 border-b border-gray-200">
            <button onclick="showSection('ongoing')" id="tab-ongoing" class="tab-button active px-4 py-2 text-sm font-medium text-gray-900 border-b-2 border-gray-900 transition">
                Sedang Berjalan
                @if($ongoingOrders->count() > 0)
                    <span class="ml-2 px-2 py-0.5 text-xs bg-gray-100 rounded-full">{{ $ongoingOrders->count() }}</span>
                @endif
            </button>
            <button onclick="showSection('canceled')" id="tab-canceled" class="tab-button px-4 py-2 text-sm font-medium text-gray-500 border-b-2 border-transparent hover:text-gray-900 transition">
                Dibatalkan
                @if($canceledOrders->count() > 0)
                    <span class="ml-2 px-2 py-0.5 text-xs bg-gray-100 rounded-full">{{ $canceledOrders->count() }}</span>
                @endif
            </button>
            <button onclick="showSection('completed')" id="tab-completed" class="tab-button px-4 py-2 text-sm font-medium text-gray-500 border-b-2 border-transparent hover:text-gray-900 transition">
                Selesai
                @if($completedOrders->count() > 0)
                    <span class="ml-2 px-2 py-0.5 text-xs bg-gray-100 rounded-full">{{ $completedOrders->count() }}</span>
                @endif
            </button>
        </div>

        <!-- Ongoing Orders Section -->
        <div id="section-ongoing" class="order-section">
            <div class="bg-white border border-gray-200 rounded-[32px] divide-y divide-gray-100">
                @forelse ($ongoingOrders as $order)
                    <a href="{{ route('orders.show', $order) }}" class="block sm:flex sm:items-center sm:justify-between gap-4 px-4 sm:px-6 py-4 sm:py-5 hover:bg-gray-50 transition">
                        <div class="flex-1">
                            <div class="flex items-center gap-3 mb-2">
                                <p class="text-xs uppercase tracking-[0.4em] text-gray-400 font-medium">{{ $order->code }}</p>
                                <x-status-badge :status="$order->status" />
                            </div>
                            <p class="text-sm text-gray-500 mb-1">{{ $order->created_at->format('d M Y, H:i') }}</p>
                            <p class="text-xs text-gray-400">{{ $order->items->count() }} item{{ $order->items->count() > 1 ? 's' : '' }}</p>
                        </div>
                        <div class="flex items-center justify-between sm:justify-end gap-4 mt-2 sm:mt-0">
                            <p class="text-base sm:text-lg font-semibold">{{ 'Rp '.number_format($order->total, 0, ',', '.') }}</p>
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
        <div class="bg-white border border-gray-200 rounded-[32px] divide-y divide-gray-100">
                @forelse ($canceledOrders as $order)
                    <a href="{{ route('orders.show', $order) }}" class="block sm:flex sm:items-center sm:justify-between gap-4 px-4 sm:px-6 py-4 sm:py-5 hover:bg-gray-50 transition opacity-75">
                        <div class="flex-1">
                            <div class="flex items-center gap-3 mb-2">
                                <p class="text-xs uppercase tracking-[0.4em] text-gray-400 font-medium">{{ $order->code }}</p>
                                <x-status-badge :status="$order->status" />
                            </div>
                            <p class="text-sm text-gray-500 mb-1">{{ $order->created_at->format('d M Y, H:i') }}</p>
                            <p class="text-xs text-gray-400">{{ $order->items->count() }} item{{ $order->items->count() > 1 ? 's' : '' }}</p>
                        </div>
                        <div class="flex items-center justify-between sm:justify-end gap-4 mt-2 sm:mt-0">
                            <p class="text-base sm:text-lg font-semibold line-through">{{ 'Rp '.number_format($order->total, 0, ',', '.') }}</p>
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
            <div class="bg-white border border-gray-200 rounded-[32px] divide-y divide-gray-100">
                @forelse ($completedOrders as $order)
                    <div class="px-4 sm:px-6 py-4 sm:py-5 hover:bg-gray-50 transition">
                        <div class="block sm:flex sm:items-center sm:justify-between gap-4">
                            <a href="{{ route('orders.show', $order) }}" class="flex-1">
                                <div class="flex items-center gap-3 mb-2">
                                    <p class="text-xs uppercase tracking-[0.4em] text-gray-400 font-medium">{{ $order->code }}</p>
                    <x-status-badge :status="$order->status" />
                                </div>
                                <p class="text-sm text-gray-500 mb-1">{{ $order->created_at->format('d M Y, H:i') }}</p>
                                <p class="text-xs text-gray-400">{{ $order->items->count() }} item{{ $order->items->count() > 1 ? 's' : '' }}</p>
                            </a>
                            <div class="flex items-center justify-between sm:justify-end gap-4 mt-2 sm:mt-0">
                                <p class="text-base sm:text-lg font-semibold">{{ 'Rp '.number_format($order->total, 0, ',', '.') }}</p>
                                <a href="{{ route('orders.show', $order) }}" class="px-4 py-2 rounded-full bg-gray-900 text-white text-xs uppercase tracking-[0.4em] hover:bg-black transition">
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
        document.addEventListener('DOMContentLoaded', function() {
            showSection('ongoing');
        });
    </script>
    @endpush
@endsection

