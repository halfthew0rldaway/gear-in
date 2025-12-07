@extends('layouts.admin')

@section('page-title', 'Dashboard')

@section('content')
    <div class="space-y-6">
        <div class="grid md:grid-cols-2 xl:grid-cols-4 gap-4">
            <div class="bg-white border border-gray-200 rounded-3xl p-5 flex flex-col justify-between min-h-[120px]">
                <p class="text-xs uppercase tracking-[0.4em] text-gray-400 mb-2">Orders Today</p>
                <p class="text-3xl font-semibold">{{ $stats['orders_today'] }}</p>
            </div>
            <div class="bg-white border border-gray-200 rounded-3xl p-5 flex flex-col justify-between min-h-[120px]">
                <p class="text-xs uppercase tracking-[0.4em] text-gray-400 mb-2">Orders This Month</p>
                <p class="text-3xl font-semibold">{{ $stats['orders_this_month'] }}</p>
            </div>
            <div class="bg-white border border-gray-200 rounded-3xl p-5 flex flex-col justify-between min-h-[120px]">
                <p class="text-xs uppercase tracking-[0.4em] text-gray-400 mb-2">Pending Orders</p>
                <p class="text-3xl font-semibold">{{ $stats['pending_orders'] }}</p>
            </div>
            <div class="bg-white border border-gray-200 rounded-3xl p-5 flex flex-col justify-between min-h-[120px]">
                <p class="text-xs uppercase tracking-[0.4em] text-gray-400 mb-2">Total Revenue</p>
                <p class="text-2xl sm:text-3xl font-semibold break-words">{{ 'Rp '.number_format($stats['revenue'], 0, ',', '.') }}</p>
            </div>
        </div>

        <div class="grid md:grid-cols-2 xl:grid-cols-4 gap-4">
            <div class="bg-white border border-gray-200 rounded-3xl p-5 flex flex-col justify-between min-h-[120px]">
                <p class="text-xs uppercase tracking-[0.4em] text-gray-400 mb-2">Revenue This Month</p>
                <p class="text-2xl sm:text-3xl font-semibold break-words">{{ 'Rp '.number_format($stats['revenue_this_month'], 0, ',', '.') }}</p>
            </div>
            <div class="bg-white border border-gray-200 rounded-3xl p-5 flex flex-col justify-between min-h-[120px]">
                <p class="text-xs uppercase tracking-[0.4em] text-gray-400 mb-2">Total Products</p>
                <div>
                    <p class="text-3xl font-semibold">{{ $stats['products'] }}</p>
                    <p class="text-xs text-gray-500 mt-1">{{ $stats['active_products'] }} active</p>
                </div>
            </div>
            <div class="bg-white border border-gray-200 rounded-3xl p-5 flex flex-col justify-between min-h-[120px]">
                <p class="text-xs uppercase tracking-[0.4em] text-gray-400 mb-2">Low Stock</p>
                <p class="text-3xl font-semibold text-orange-600">{{ $stats['low_stock_products'] }}</p>
            </div>
            <div class="bg-white border border-gray-200 rounded-3xl p-5 flex flex-col justify-between min-h-[120px]">
                <p class="text-xs uppercase tracking-[0.4em] text-gray-400 mb-2">Total Customers</p>
                <p class="text-3xl font-semibold">{{ $stats['customers'] }}</p>
            </div>
        </div>
    </div>

    <div class="grid lg:grid-cols-2 gap-6 mt-6">
        <div class="bg-white border border-gray-200 rounded-[32px] p-6 flex flex-col">
            <div class="mb-6">
                <p class="text-xs uppercase tracking-[0.4em] text-gray-400">Sales & Orders</p>
                <h2 class="text-xl font-semibold">7 Hari Terakhir</h2>
            </div>
            <div class="relative flex-1" style="height: 300px; max-height: 300px; overflow: hidden;">
                <canvas id="salesChart"></canvas>
            </div>
        </div>

        <div class="bg-white border border-gray-200 rounded-[32px] p-6 flex flex-col">
            <div class="mb-6">
                <p class="text-xs uppercase tracking-[0.4em] text-gray-400">Revenue Trend</p>
                <h2 class="text-xl font-semibold">6 Bulan Terakhir</h2>
            </div>
            <div class="relative flex-1" style="height: 300px; max-height: 300px; overflow: hidden;">
                <canvas id="revenueChart"></canvas>
            </div>
        </div>
    </div>

    @if($lowStockProducts->count() > 0)
    <div class="bg-white border border-gray-200 rounded-[32px] p-6 mt-6">
        <div class="flex items-center justify-between mb-4">
            <div>
                <p class="text-xs uppercase tracking-[0.4em] text-gray-400">Low Stock Alert</p>
                <h2 class="text-xl font-semibold">Produk dengan stok rendah</h2>
            </div>
            <a href="{{ route('admin.products.index') }}" class="text-xs uppercase tracking-[0.4em] text-gray-400 hover:text-gray-900">Lihat semua</a>
        </div>
        <div class="divide-y divide-gray-100">
            @foreach ($lowStockProducts as $product)
                <div class="grid grid-cols-1 sm:grid-cols-12 gap-4 py-4 items-center">
                    <div class="sm:col-span-9">
                        <p class="text-sm font-semibold">{{ $product->name }}</p>
                        <p class="text-xs text-gray-500 mt-1">{{ $product->category->name }}</p>
                    </div>
                    <div class="sm:col-span-3 flex justify-start sm:justify-end">
                        <p class="text-sm font-semibold text-orange-600">Stock: {{ $product->stock }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    @endif

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
                <div class="grid grid-cols-1 sm:grid-cols-12 gap-4 py-4 items-center">
                    <div class="sm:col-span-5">
                        <p class="text-sm font-semibold">{{ $order->code }}</p>
                        <p class="text-xs text-gray-500 mt-1">{{ $order->user->name }}</p>
                    </div>
                    <div class="sm:col-span-4 flex justify-start sm:justify-center">
                        <p class="text-sm sm:text-base font-semibold">{{ 'Rp '.number_format($order->total, 0, ',', '.') }}</p>
                    </div>
                    <div class="sm:col-span-3 flex justify-start sm:justify-end">
                        <x-status-badge :status="$order->status" />
                    </div>
                </div>
            @empty
                <p class="text-sm text-gray-500 py-4 text-center">Belum ada pesanan.</p>
            @endforelse
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script>
        // Sales & Orders Chart (Last 7 Days)
        const salesCtx = document.getElementById('salesChart');
        if (salesCtx) {
            new Chart(salesCtx, {
                type: 'line',
                data: {
                    labels: @json($chartLabels),
                    datasets: [
                        {
                            label: 'Revenue (Rp)',
                            data: @json($chartRevenue),
                            borderColor: '#1f1f1f',
                            backgroundColor: 'rgba(31, 31, 31, 0.1)',
                            tension: 0.4,
                            yAxisID: 'y',
                            fill: true
                        },
                        {
                            label: 'Orders',
                            data: @json($chartOrders),
                            borderColor: '#6f6f6f',
                            backgroundColor: 'rgba(111, 111, 111, 0.1)',
                            tension: 0.4,
                            yAxisID: 'y1',
                            fill: true
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: true,
                            position: 'top',
                            labels: {
                                font: {
                                    family: 'Space Grotesk, sans-serif',
                                    size: 12
                                },
                                padding: 15,
                                usePointStyle: true
                            }
                        },
                        tooltip: {
                            backgroundColor: 'rgba(31, 31, 31, 0.9)',
                            padding: 12,
                            titleFont: {
                                family: 'Space Grotesk, sans-serif',
                                size: 12,
                                weight: '600'
                            },
                            bodyFont: {
                                family: 'Space Grotesk, sans-serif',
                                size: 11
                            },
                            callbacks: {
                                label: function(context) {
                                    if (context.datasetIndex === 0) {
                                        return 'Revenue: Rp ' + context.parsed.y.toLocaleString('id-ID');
                                    } else {
                                        return 'Orders: ' + context.parsed.y;
                                    }
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            grid: {
                                display: false
                            },
                            ticks: {
                                font: {
                                    family: 'Space Grotesk, sans-serif',
                                    size: 11
                                },
                                color: '#6f6f6f'
                            }
                        },
                        y: {
                            type: 'linear',
                            display: true,
                            position: 'left',
                            grid: {
                                color: 'rgba(223, 223, 223, 0.5)'
                            },
                            ticks: {
                                font: {
                                    family: 'Space Grotesk, sans-serif',
                                    size: 11
                                },
                                color: '#6f6f6f',
                                callback: function(value) {
                                    return 'Rp ' + value.toLocaleString('id-ID');
                                }
                            }
                        },
                        y1: {
                            type: 'linear',
                            display: true,
                            position: 'right',
                            grid: {
                                drawOnChartArea: false
                            },
                            ticks: {
                                font: {
                                    family: 'Space Grotesk, sans-serif',
                                    size: 11
                                },
                                color: '#6f6f6f'
                            }
                        }
                    }
                }
            });
        }

        // Revenue Chart (Last 6 Months)
        const revenueCtx = document.getElementById('revenueChart');
        if (revenueCtx) {
            new Chart(revenueCtx, {
                type: 'bar',
                data: {
                    labels: @json($monthlyLabels),
                    datasets: [{
                        label: 'Revenue (Rp)',
                        data: @json($monthlyRevenue),
                        backgroundColor: 'rgba(31, 31, 31, 0.8)',
                        borderColor: '#1f1f1f',
                        borderWidth: 1,
                        borderRadius: 8
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            backgroundColor: 'rgba(31, 31, 31, 0.9)',
                            padding: 12,
                            titleFont: {
                                family: 'Space Grotesk, sans-serif',
                                size: 12,
                                weight: '600'
                            },
                            bodyFont: {
                                family: 'Space Grotesk, sans-serif',
                                size: 11
                            },
                            callbacks: {
                                label: function(context) {
                                    return 'Revenue: Rp ' + context.parsed.y.toLocaleString('id-ID');
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            grid: {
                                display: false
                            },
                            ticks: {
                                font: {
                                    family: 'Space Grotesk, sans-serif',
                                    size: 11
                                },
                                color: '#6f6f6f'
                            }
                        },
                        y: {
                            grid: {
                                color: 'rgba(223, 223, 223, 0.5)'
                            },
                            ticks: {
                                font: {
                                    family: 'Space Grotesk, sans-serif',
                                    size: 11
                                },
                                color: '#6f6f6f',
                                callback: function(value) {
                                    return 'Rp ' + value.toLocaleString('id-ID');
                                }
                            }
                        }
                    }
                }
            });
        }
    </script>
    @endpush
@endsection

